@extends('layouts.app')

@section('content')
<div class="w3-white w3-mobile" style="max-width: 1000px;padding: 20px 20px; margin: 50px auto;">
  <h2>Select Problem Type To Add as Skill</h2>
  <h3>Editing Skill for: <span class="editbutton modalOpener" value="/users/{{$user->id}}/compact">{{$user->forename}} {{$user->surname}}</span></h3>
  <form id="addProblemTypeForm">
  <table id='problem-table' class="display cell-border stripe hover">
    <thead>
      <tr>
        <th>Problem Type ID</th><th>Problem Type Name</th><th>Select</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($problem_types as $pt)
      <tr>
        <td style="text-align: right;">{{sprintf('%04d', $pt->id)}}</td>
        <td title="View" class="editbutton modalOpener" value='/problem_types/{{$pt->id}}/compact'>
          @if (!is_null($pt->pID))
            ({{$pt->pDesc}})
          @endif
          {{$pt->description}}
        </td>
        <td title="Select" class="selectBox editbutton" style="text-align: center;">
          <input class="selectRadio" type="radio" name='ptype' value="{{$pt->id}}" />
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div style="text-align: center;">
        <input id="addProblemType" class="bigbutton w3-card w3-button w3-row" type="submit" value="Edit Problem Type" disabled/>
    </div>
    </form>
</div>

<script>
$(document).ready( function () 
{
    var problem_type_id = <?php echo $skill->problem_type_id; ?>;

    $('.selectBox').click(function ()
    {
      $(this).children('.selectRadio').prop('checked', true);
      $('#addProblemType').prop('disabled', false);
    });

  $('input:radio[name="ptype"]').change(
      function(){
        $('#addProblemType').prop('disabled', false);
    });
  var page = 0;
  $('input:radio[name="ptype"]').each(function (i, r)
    {
      var radio = $(r);
      if (radio.val() == problem_type_id)
      {
        page = i/10;
        radio.prop('checked', true);
        $('#addProblemType').prop('disabled', false);
      }
    });

    $('#addProblemTypeForm').submit(function ()
    {
        window.location.href = '/skills/{{$user->id}}/{{$skill->id}}/edit/' + $("input[name='ptype']:checked").val();

        return false;
    });
    var table = $('#problem-table').DataTable();

    // If we provide some sort of search term through the redirect, search it here.
    var search = "<?php if (session('search')) echo session('search'); ?>";
    // table.search(search).draw();
    table.page(Math.floor(page)).draw('page');
    console.log(page);
});

</script>

@endsection