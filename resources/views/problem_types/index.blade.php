@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
	<h2>Main Problem Types</h2>
	<!-- List of Problem types with informtaion about them -->
	<table id='problem-table' class="display cell-border stripe hover" style="width:100%;">
		<thead>
			<tr>
				<th>Problem Type ID</th><th>Problem Type Name</th><th>Specialists</th><th>Active Problems</th><th>---</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($parents as $problem_type)
			<tr>
				<td style="text-align: right;">{{sprintf("%04d", $problem_type->id)}}</td>
				<td>{{$problem_type->description}}</td>
				<td style="text-align: right;">{{$problem_type->specialists}}</td>
				<td style="text-align: right;">{{$problem_type->problems}}</td>
				<td class="editbutton" onclick="window.location.href = '/problem_types/{{$problem_type->id}}';" style="text-align: center;">
					View/Edit
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	<!-- Button to create new problem type -->
	<div style="text-align: center;">
        <a class="blank" href="/problem_types/create">
            <div class="bigbutton w3-card w3-button w3-row">
                Create New Problem Type
            </div>
        </a><br />
	</div>
</div>
</style>

<script>
$(document).ready( function ()
{
    var table = $('#problem-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    table.search(search).draw();
});
</script>

@endsection
