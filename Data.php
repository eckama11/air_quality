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
			<link rel="stylesheet" href="css/bootstrap.min.css" />
			<link rel="stylesheet" href="css/custom.css" />
			<style type="text/css">
			input { max-width: 100%; }
			
			</style>
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
    		<script>
				require(["main"], function() {
					// Init date picker and display UI
					$('[data-provide="datepicker"]').datepicker();
				});

			</script>
    	</head>
 
		<body>
			<!-- <h1 style="text-align:center;">Readings as of <?php echo date('F d, Y'); ?></h1>
			THIS IS FOR BUG FIXING
			<?php echo $js_array; ?>
			<table id="readings">
			<tr>
				<th>Reading Id</th>
				<th>Sensor Id</th>
				<th>DateTime</th>
				<th>Temperature</th>
				<th>Humidity</th>
				<th>Pressure</th>
				<th>Altitude</th>
				<th>Latitude</th>
				<th>Longitude</th>
				<th>Particles (ppm)</th>
			</tr>
			<?php
				/*foreach($sensor as $reading){
					$values = $reading->toArray();
					echo "<tr>";
					foreach($values as $key=>$val){
						echo "<td>$val</td>";
					}
					echo "</tr>";
				}*/
			?>
			</table>
			-->
			
            <div>
            	<input data-provide="datepicker" data-date-autoclose="true" data-date-today-btn="true" data-date-today-highlight="true" class="form-control" type="text" name="startDate" id="startDate" placeholder="Enter employment start date" />
			</div>
			<div></div>
			<div id="chart_div" style="width: 90%; height: 500px; margin: auto; border: 2px black solid;"></div>
		</body>
	</html>
	