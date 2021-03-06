@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Problem ID: {{sprintf('%04d', $problem->id)}}</h2>
    <h3>Initial Caller: {{$caller->forename}} {{$caller->surname}}</h3>
    <form id="addUserForm">
    <!-- List of Users -->
    <table id='user-table' class="display cell-border stripe hover">

        <thead>
            <tr>
                <th>Employee ID</th><th>Name</th><th>Department</th><th>Job Title</th><th>Phone Number</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td style="text-align: right;">{{sprintf('%04d',$user->employee_id)}}</td>
                <td class="editbutton modalOpener" value="/users/{{$user->id}}/compact">{{$user->forename}} {{$user->surname}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->title}}</td>
                <td>{{$user->phone_number}}</td>
                <td class="selectBox editbutton" style="text-align: center;">
                    <input class="selectRadio" type="radio" name='existing' value="{{$user->id}}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Button to Create call for user -->
    <div style="text-align: center;">
        <input id="addUser" class="bigbutton w3-card w3-button w3-row" type="submit" value="Create Call for User" disabled/>
    </div>
    </form>
</div>


<script>
var problem;
$(document).ready( function ()
{
    problem = <?php echo json_encode($problem) ?>;
    //Enable button if a user is selected or changed
    $('input:radio[name="existing"]').change(
    function(){
        $('#addUser').prop('disabled', false);
    });

    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addUser').prop('disabled', false);
    });
    //Submit action for form
    $('#addUserForm').submit(function ()
    {
        window.location.href = '/problems/' + problem.id + '/add_call/' + $("input[name='existing']:checked").val();

        return false;
    })
    var table = $('#user-table').DataTable();

});
</script>

@endsection
