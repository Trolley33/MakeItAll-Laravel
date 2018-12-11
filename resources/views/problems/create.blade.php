@extends('layouts.app')

<style>
    .call_menu
    {
        border-radius: 2px;
        margin-top: 30px;
        position: absolute;
        left: 20%;
        width: 60%;
        min-width: 300px;
        background-color: white;
        margin-bottom: 100px;
    }

    #info-table{
        width: 90%;
        margin-left: 5%;
    }

    #info-table td{
        padding: 10px;
    }

    #info-table th{
        padding: 10px;
        background-color: lightgrey;
    }
    .editbutton:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }

    .slideHeader:hover
    {
        background-color: #BBBBBB !important;
        cursor: pointer;
    }
</style>

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
        <div>
            <div class="w3-padding-large w3-white">
                <h2>Problem Viewer</h2>
                <table id="info-table">
                    <tbody>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Number</th>
                            <td> #{{$problem->id}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Problem Type</th>
                            <td>
                                {{$problem->problem_type}}  
                            </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Description</th>
                            <td> {{$problem->description}} </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Notes</th>
                            <td> {{$problem->notes}} </td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Assigned Helper</th>
                            <td class="editbutton" onclick="window.location.href = '/users/{{$specialist->id}}';">{{$specialist->forename}} {{$specialist->surname}}</td>
                        </tr>
                        <tr class="w3-hover-light-grey solve">
                            <th>Status</th>
                            @if (count($resolved) === 1)

                                <td class="w3-green" > Solved 
                                </td>
                            @else
                                <td class="w3-red" > Unsolved </td>
                            @endif
                        </tr>
                        @if (count($resolved) === 1)

                            <tr id = "8" class="w3-hover-light-grey solve">
                                <th>Solution Notes</th>
                                <td>
                                @foreach ($resolved as $r)
                                    {{$r->solution_notes}}
                                @endforeach
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div style="text-align: center;">
                    <a class="blank" href="/problems/{{$problem->id}}/edit">
                        <div class="menu-item w3-card w3-button w3-row" style="width: 400px;">
                            Edit Details
                        </div>
                    </a><br />
                </div>
                <hr />
            </div>
        </div>
    </div>

    <script>

    function pad(v)
    {
        v=v.toString();
        if(v.length == 1) return "0" + ""+ v;
        else return v;
    }

    $(document).ready(function () {
    });
    </script>
@endsection