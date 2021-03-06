@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Reviewing Caller: {{$caller->forename}} {{$caller->surname}}</h2>
            <hr />
            <div style="width: 600px; margin: auto;">
            	<!-- Dropdown for hotswapping datasets -->
            	<select id="data-changer" onchange="swapDataSet()" style="width: 50%">
            		@foreach ($datasets as $i=>$d)
            			<option value="{{$i}}">{{$d['yLabel']}}</option>
            		@endforeach
            	</select>
            	<br /><br />
            	<!-- Date selector for graph -->
            	<input id="start" type="date" /> - <input id="end" type="date" /> <button onclick="changeRange()">↺</button> <button onclick="resetRange()">✖</button>
            	<canvas width="600" height="300" id='graph'>
            	</canvas>
            	<hr />
            	<!-- Table of this users most commonly called about types of problems -->
            	<h3>Most Common Types of Problem</h3>
            	<table id="pt-table" class="display cell-border stripe hover slidable" style="width:100%;">
					<thead>
                        <tr>
                            <th>Problem Type</th><th>Number of Calls Related to Problem Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($types as $pt)
                        <tr>
                            <td class="editbutton modalOpener" value='/problem_types/{{$pt->id}}/compact';">
                            @if ($pt->parent_description != '0')
					            ({{$pt->parent_description}})
					          @endif
					          {{$pt->description}}
					      	</td>
                            <td>{{$pt->count}}</td>
                        </tr>
                        @endforeach
                    </tbody>
            	</table>
        	</div>
        	</div>
        </div>
    </div>
</div>

<script>
var myChart;
var sets = [];

$(document).ready( function () 
{
    var chart = $('#graph');
    // Convert PHP array to json object(s), usable in chart.js
    <?php
    	foreach ($datasets as $key => $value) {
    		echo "sets.push({";
    			echo "yLabel: '". $value['yLabel']."', ";
    			echo "dataset: {";
    				echo "label: '',";
    				echo "backgroundColor: '".$value['color']."', ";
	    			echo "data: ". json_encode($value['data']);
	    		echo "}";
	    	echo "});";
    	}
    ?>
    // Initialise graph with options
    myChart = new Chart(chart, {
		type: 'bar',
		data: {
		    datasets : [
		    ]
		},
		options: {
	        scales: {
	            xAxes: [{
	            	bounds: 'ticks',
	                type: 'time',
	                time: {
	                	unit: 'week'
				    },
				    ticks: {
				    	source: 'auto'
				    },
				    barPercentage: 1.0,
				    categoryPercentage: 1.0
	            }],
	            yAxes: [{
	            	scaleLabel: {
	            		display: true
	            	},
	            	ticks: {
	            		beginAtZero: true
	            	}
	            }]
	        },
	        // Container for pan options
			pan: {
				enabled: true,
				mode: 'x',
				speed: 10,
				threshold: 10
			},
			zoom: {
				enabled: true,
				mode: 'x',
				limits: {
					max: 10,
					min: 0.5
				}
			},
	    }
	});
    // Select 1st dataset by default
	swapDataSet(sets[0]);
	// Initialise datatable, sorting by calls in descending order
	$('#pt-table').DataTable({
		order: [[1, 'desc']]
	});

});
</script>

@endsection
