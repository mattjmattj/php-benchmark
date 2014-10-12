<?php

$distributions = require('benchmark.php');

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 

		<title>Demo page for php-benchmark : various array_map implementations</title>
		
		<script type="text/javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>
		
	</head>
	<body>
		<h1>Demo page for php-benchmark : various array_map implementations</h1>
		<div id="chart"></div>
		<div>
			<pre>
				<code>
<?php
echo htmlentities(file_get_contents(__DIR__.'/implementations.php'));
?>
				</code>
			</pre>
		</div>
		<script type="text/javascript">
		$(function () {
			$('#chart').highcharts({
				chart: {
					type: 'spline'
				},
				title: {
					text: 'Execution time of various array_map implementations, for mapping 10000 values',
				},
				xAxis: {
					title: {
						text: 'Iterations'
					},
					labels: {
						formatter: function () {
							return "";
						}
					}
				},
				yAxis: {
					title: {
						text: 'Time (s)'
					},
					min: 0
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'middle',
					borderWidth: 0
				},
				series:
<?php
$series = [];
foreach ($distributions as $name => $distribution) {
	$serie = [
		'name' => $name,
		'data' => []
	];
	foreach ($distribution as $k => $v) {
	
		//we kick too big values away
		if ($v > 0.05) continue;
	
		$serie['data'][] = [$k,$v];
	}
	
	$series[] = $serie;
}
echo json_encode($series);
?>
				
			});
		});
		</script>
	</body>
</html>