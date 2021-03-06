@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$equipment->description}}</h2>
            <!-- Form to edit the  Equipment details -->
            {!! Form::open(['action' => ['EquipmentController@update', $equipment->id], 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <!-- Text boxes populated with current details of the Equipment -->
                    <tr class="w3-hover-light-grey">
                        <th>Serial Number</th>
                        <td>{{Form::text('serialNumber', $equipment->serial_number, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'Serial No.'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Description</th>
                        <td>{{Form::text('desc', $equipment->description, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'Description'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Model</th>
                        <td>{{Form::text('model', $equipment->model, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'Model'])}}</td>
                    </tr>
                </tbody>
            </table>
            <!-- Button to submit changes made to Equipment details -->
            {{Form::hidden('_method', 'PUT')}}

            {{Form::submit('Submit Changes', ['class'=> "bigbutton w3-card w3-button w3-row w3-teal"])}}

            {!! Form::close() !!}

            <br />
        </div>
    </div>
</div>

<script>
$(document).ready(function()
{
});
</script>
@endsection
