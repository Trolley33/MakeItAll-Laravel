@extends('layouts.app')

@section('content')
<div class="call_menu w3-center w3-padding w3-light-grey">
    <div>
        <div class="w3-padding-large w3-white">
            <h2>Reviewing Specialist {{$caller->forename}} {{$caller->surname}}</h2>
            <hr />
            <div style="width: 600px; margin: auto;">
            	<select id="data-changer" onchange="swapDataSet()">
            		@foreach ($datasets as $i=>$d)
            			<option value="{{$i}}">{{$d['yLabel']}}</option>
            		@endforeach
            	</select>
            	<br />
            	<input id="start" type="date" /> - <input id="end" type="date" /> <button onclick="changeRange()">↺</button>
            	<canvas width="600" height="300" id='graph'>
            	</canvas>
            	<hr />
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

	swapDataSet(sets[0]);

	$('#pt-table').DataTable({
		order: [[1, 'desc']]
	});

});
function swapDataSet()
{
	var to = sets[$('#data-changer').val()];

	myChart.options.scales.yAxes[0].scaleLabel.labelString = to.yLabel;
	myChart.data.datasets[0] = to.dataset;
	myChart.update();
}

function changeRange()
{
	var start = $('#start');
	var end = $('#end');
	myChart.options.scales.xAxes[0].time.min = start.val();
	myChart.options.scales.xAxes[0].time.max = end.val();
	myChart.update();
}
</script>

@endsection
