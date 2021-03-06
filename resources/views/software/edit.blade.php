@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>{{$software->description}}</h2>
            <!-- Textboxes autofilled with previous information, that can be edited -->
            {!! Form::open(['action' => ['SoftwareController@update', $software->id], 'method' => 'POST']) !!}
            <table>
                <tbody>
                    <tr class="w3-hover-light-grey">
                        <th>Name</th>
                        <td>{{Form::text('name', $software->name, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'Name'])}}</td>
                    </tr>
                    <tr class="w3-hover-light-grey">
                        <th>Description</th>
                        <td>{{Form::text('desc', $software->description, ['class'=>'w3-input w3-border w3-round', 'placeholder'=>'Description'])}}</td>
                    </tr>
                </tbody>
            </table>
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
