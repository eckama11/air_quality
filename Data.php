<?php
require_once("common.php");
    if (!isset($loginSession))
        doUnauthenticatedRedirect();
        
$sensor = $db->readSensors();

$js_array = $db->readTemp();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<base href="<?php echo htmlentities(BASE_URL); ?>">
			<title>Readings</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<!-- StyleSheet -->
			
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    		<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {
					var data = google.visualization.arrayToDataTable(<?= json_encode($js_array); ?>);

					var options = {
					  title: 'Temperature Readings'
					};

					var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
					chart.draw(data, options);
				  }
    		</script>
    		<script type="text/javascript">
				// Init date picker and display UI
				$('#datepicker').datepicker();
			</script>
    	</head>
		<body>
    		<input type="text" style="width: 30%; margin: auto;" id="datepicker" data-provide="datepicker" placeholder="Click to choose a date..." />
			<br />
			<div id="chart_div" style="width: 90%; height: 500px; margin: auto; border: 2px black solid;"></div>
		</body>
	</html>
	