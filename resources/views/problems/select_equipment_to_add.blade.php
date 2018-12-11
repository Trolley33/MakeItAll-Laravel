@extends('layouts.app')

<style>
.editbutton:hover
{
    background-color: #BBBBBB !important;
    cursor: pointer;
}
</style>

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
    <h2>Problem ID: {{$problem->id}}</h2>
    {!! Form::open(['action' => ['ProblemController@append_equipment', $problem->id], 'method' => 'POST', 'id'=>'addEquipmentForm']) !!}

    {{Form::hidden('problem-id', $problem->id)}}
    <table id='equipment-table' class="display cell-border stripe hover">

        <thead>
            <tr>
                <th>Serial Number</th><th>Description</th><th>Model</th><th>Select</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equipment as $e)
                <?php $flag = true; ?>
                @foreach ($affected as $a)
                    @if ($e->id == $a->equipment_id)
                            <?php
                            $flag = false; 
                            break
                            ;?>
                    @endif
                @endforeach
                @if ($flag)
                <tr>
                    <td>{{$e->serial_number}}</td>
                    <td>{{$e->description}}</td><td>{{$e->model}}</td>
                    <td style="text-align: center;">
                        <input type="checkbox" name='equipment[]' value="{{$e->id}}" />
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>

        
    </table>
    <div style="text-align: center;">
        <input id="addEquipment" class="menu-item w3-card w3-button w3-row" type="submit" value="Add Equipment to Problem" style="width: 400px;" disabled/>
    </div>
    {!! Form::close() !!}
</div>


<script>
var problem;
$(document).ready( function () 
{
    problem = <?php echo json_encode($problem) ?>;
    var table = $('#equipment-table').DataTable();

    $('input:checkbox[name="equipment[]"]').change(
    function(){
        if ($('input:checkbox[name="equipment[]"]:checked').length > 0)
        {
            $('#addEquipment').prop('disabled', false);
        }
        else
        {
            $('#addEquipment').prop('disabled', true);
        }
    });

});
</script>

@endsection
