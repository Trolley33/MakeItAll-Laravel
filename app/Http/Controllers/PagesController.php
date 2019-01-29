<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\User;
use App\Job;
use App\Reassignments;

class PagesController extends Controller
{

    // Function that returns array of 'links' and their corrosponding display text. By using a public static function we get around using a global variable which caused problems. Can be accessed from any other controllers.
    public static function getOperatorLinks ()
    {
        // href = which main page to redirect to, text = what to display.
        return [
            ['href'=>'back','text'=>'back'],
            ['href'=>'operator','text'=>'Home'],
            ['href'=>'problems','text'=>'Problems'],
            ['href'=>'users','text'=>'Users'],
            ['href'=>'departments','text'=>'Departments'],
            ['href'=>'jobs','text'=>'Jobs'],
            ['href'=>'equipment','text'=>'Equipment'],
            ['href'=>'software','text'=>'Software'],
            ['href'=>'problem_types','text'=>'Problem Types']
        ];
    }

    // Function that returns array of 'links' and their corrosponding display text. By using a public static function we get around using a global variable which caused problems. Can be accessed from any other controllers.
    public static function getSpecialistLinks ()
    {
        // href = which main page to redirect to, text = what to display.
        return [
            ['href'=>'back','text'=>'back'],
            ['href'=>'specialist','text'=>'Home'],
            ['href'=>'problems', 'text'=>'Problems']
        ];
    }

    // Function that returns array of 'links' and their corrosponding display text. By using a public static function we get around using a global variable which caused problems. Can be accessed from any other controllers.
    public static function getAnalystLinks ()
    {
        // href = which main page to redirect to, text = what to display.
        return [
            ['href'=>'back','text'=>'back'],
            ['href'=>'specialist','text'=>'Home']
        ];
    }
    // Function that checks if the currently signed in user has the access level passed as a parameter. Returns true/false.
    public static function hasAccess($level)
    {
        // Check that the csrf (token) cookie is set, and set it to a variable.
        if (isset($_COOKIE['csrf']))
        {
            $cookie = $_COOKIE['csrf'];
        }
        // If unset then the user isn't logged in at all so return false.
        else
        {
            return false;
        }
        
        // Find the user that has the crsf cookie linked to their account.
        $result = DB::table('users')->select('users.id')->where('users.remember_token', '=', $cookie)->get();

        // If there is exactly 1 user with the token, then the user is definitely logged in.
        if (!is_null($result) && count($result) == 1)
        {
            // Find the access level of the user from their job.
            $id = $result->first()->id;
            $user = User::find($id);
            $job = Job::find($user->job_id);
            // If the access level matches the parameter, return true.
            if ($job->access_level == $level)
            {
                return true;
            }
        }

        return false;
    }

    // Function that gets the currently logged in user as a 'User' object quickly.
    public static function getCurrentUser()
    {
        // Check that the user is logged in as 1 of the 3 accepted accounts, as this ensures the cookie is set.
        if (PagesController::hasAccess(1) || PagesController::hasAccess(2) || PagesController::hasAccess(3))
        {
            // Find user who has the csrf cookie.
            $cookie = $_COOKIE['csrf'];
            $user = User::where('remember_token', '=', $cookie)->get()->first();

            return $user;
        }
        // If the user can't be verified to have any access level, return null to prevent malicious access.
        return null;
    }

    public function index()
    {
        // If user is logged in, redirect them to their homepage.
        if (PagesController::hasAccess(1))
        {
            return redirect('/operator');
        }
        elseif (PagesController::hasAccess(2))
        {
            return redirect('/specialist');
        }
        elseif (PagesController::hasAccess(3))
        {
            return redirect('/analyst');
        }
        // If user is not logged in, show main index page.
        $data = array(
            'title' => "Make-It-All Helpdesk",
            'desc' => "For submitting and receiving tehnical queries."
        );
        return view('pages.index')->with($data);
    }

    public function login()
    {
        // If user is already logged in, redirect them to their homepage.
        if (PagesController::hasAccess(1))
        {
            return redirect('/operator');
        }
        if (PagesController::hasAccess(2))
        {
            return redirect('/specialist');
        }
        if (PagesController::hasAccess(3))
        {
            return redirect('/analyst');
        }
        // If user is not logged in, show login page.
        $data = array(
            'title' => "Login Page",
            'desc' => "Please log in with your user credentials below."
        );

        return view('pages.login')->with($data);
    }

    public function logout()
    {
        // Delete csrf token (set it to 0, and expire it).
        setcookie("csrf", "", time()-3600);
        // Go back to index page.
        return redirect('/')->with('success', 'Logged Out');
    }

    public function FAQ()
    {
        // Load FAQ page, no access required.
        $data = array(
            'title' => "FAQ",
            'desc' => ""
        );

        return view('pages.faq')->with($data);
    }

    // Function to verify a login attempt.
    public function verify()
    {
        $name = $_POST['username'];
        // Password stored as plain-text as HR's system would handle actual login attempts (as discussed on the forum), this serves as a temporary way to demonstrate logging in.
        $pass = $_POST['password'];
        // Token generated from form.
        $token = $_POST['tok'];

        // If username and password are correct.
        $result = DB::table('users')->select('users.id')->where('users.username', '=', $name)->where('users.password', '=', $pass)->get();
        if (!is_null($result) && count($result) == 1)
        {
            // Get information about user.
            $id = $result->first()->id;
            $user = User::find($id);
            $job = Job::find($user->job_id);

            // Set csrf token to expire in 24 hours.
            setcookie('csrf', $token, time() + 86400, "/");
            // Set token in database (preventing logging in from multiple machines, which could cause problems).
            $user->remember_token = $token;
            $user->save();

            // Redirect user to their appropriate homepage.
            $level = $job->access_level;
            if ($level == 1)
            {
                return redirect('operator/');
            }
            if ($level == 2)
            {
                return redirect('specialist/');
            }
            if ($level == 3)
            {
                return redirect('analyst/');
            }
        }
        // If any of this fails, redirect to login page.
        return redirect('login')->with('error', 'Invalid username/password.');

    }

    // ==== Operator pages. ====
    public function operator_homepage()
    {
        // If user allowed to access this page.
        if (PagesController::hasAccess(1))
        {
            $me = PagesController::getCurrentUser();

            // Get intial caller for problem.
            $reassignments = DB::select(DB::raw(
                'SELECT problems.id as pID, problems.created_at, problem_types.description as ptDesc, problem_types.id as ptID, problems.description, IFNULL(parents.description,0) as pDesc, problems.importance, users.forename, users.surname, users.id as uID, calls.id as cID, IFNULL(specialists.forename,0) as sForename, IFNULL(specialists.surname,0) as sSurname, IFNULL(specialists.id,0) as sID, importance.text, importance.class, importance.level, reassignments.reason
                FROM problems
                JOIN calls
                ON (
                    problems.id = calls.problem_id
                    AND calls.created_at = (
                        SELECT MIN(created_at)
                        FROM calls
                        WHERE problem_id = problems.id
                    )
                )
                JOIN users
                ON users.id = calls.caller_id
                JOIN problem_types
                ON problem_types.id = problems.problem_type
                JOIN importance
                ON importance.id = problems.importance
                JOIN reassignments
                ON reassignments.problem_id = problems.id 
                LEFT JOIN users specialists
                ON (specialists.id = problems.assigned_to) OR (specialists.id = reassignments.specialist_id)
                LEFT JOIN problem_types parents
                ON problem_types.parent = parents.id
                WHERE problems.logged_by = "'.$me->id.'";'
            ));

            // Supply data to view.
            $data = array(
                'title' => "Operator Homepage",
                'desc' => "Please select a task.",
                'unassigned'=>$reassignments,
                'links'=>PagesController::getOperatorLinks(),
                'active'=>'Home'
            );
            return view('pages.operator.homepage')->with($data);
        }
        // No access redirects to login page.
        return redirect('login')->with('error', 'Please log in first.');
    }


    // ==== Specialist pages. ====
    public function specialist_homepage()
    {
        // If user allowed to access this page.
        if (PagesController::hasAccess(2))
        {
            $data = array(
                'title'=> "Specialist Homepage",
                'desc'=>"Please select a task.",
                'links'=>PagesController::getSpecialistLinks(),
                'active'=>'Home'
            );

            return view('pages.specialist.homepage')->with($data);
        }
        // No access redirects to login.
        return redirect('login')->with('error', 'Please log in first.');
    }


    // ==== Analyst pages. ====
    public function analyst_homepage()
    {
        // TODO
        if (PagesController::hasAccess(3))
        {
            $data = array(
                'title' => "Analyst Homepage",
                'desc' => "Please select a task.",
                'links'=>PagesController::getAnalystLinks(),
                'active'=>'Home'
            );
            return view('pages.analyst.homepage')->with($data);
        }
        return redirect('login')->with('error', 'Please log in first.');
    }
}
