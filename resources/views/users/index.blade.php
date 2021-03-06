@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<!-- List of users and Information about them -->
	<table id='user-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Employee ID</th><th>Full Name</th><th>Department</th><th>Phone Number</th><th>Account Type</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($info as $user)
			<tr>
				<td style="text-align: right;">{{sprintf('%04d',$user->employee_id)}}</td><td>{{$user->forename}} {{$user->surname}}</td><td>{{$user->name}}</td><td>{{$user->phone_number}}</td>
				<td>
					@if ($user->access_level == 1)
						Operator
					@elseif ($user->access_level == 2)
						Specialist
					@elseif ($user->access_level == 3)
						Analyst
					@else
						Caller
					@endif
				</td>
				<td class="editbutton" onclick="window.location.href = '/users/{{$user->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach

		</tbody>
	</table>
	<!-- Button to create new Technical Support Account -->
	<div style="text-align: center;">
        <a class="blank" href="/users/create/tech-support">
            <div class="bigbutton w3-card w3-button w3-row">
                Create New Technical Support Account
            </div>
        </a><br />
				<!-- Button to create new Caller Account -->
        <a class="blank" href="/users/create/caller">
            <div class="bigbutton w3-card w3-button w3-row">
                Create New Caller Account
            </div>
        </a><br />
	</div>
</div>

<script>
$(document).ready( function ()
{
    var table = $('#user-table').DataTable();
    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
