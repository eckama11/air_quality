<?php
require_once("common.php");
    if (!isset($loginSession))
        doUnauthenticatedRedirect();
        
//$sensor = $db->readSensors();
$device = $loginSession->authenticatedUser->deviceId;
$date = date('Y-m-d');
$js_array = $db->readHumidity($date,$device);
$readingCount = count($js_array);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<base href="<?php echo htmlentities(BASE_URL); ?>">
			<title>Readings</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<!-- StyleSheet -->
    		<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {
					var data = google.visualization.arrayToDataTable(<?= json_encode($js_array); ?>);

					var options = {
					  title: 'Humidity Readings'
					};

					var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
					chart.draw(data, options);
				  }
    		</script>
    		<script type="text/javascript">
				window.onresize = resize;

				function resize()
				{
					window.location.reload();
				}
			</script>
    		<script type="text/javascript">
				// Init date picker and display UI
				$('#datepicker').datepicker({
					format: 'yyyy-mm-dd',
					autoclose: true,
					clearBtn: true,
					todayBtn: true
				});
			</script>
    	</head>
		<body>
		
    		<input type="text" class="form-control" style="display: block; text-align:center; width: 20%; margin-left: auto; margin-right: auto; border: 2px black solid;" id="datepicker" 
    		data-provide="datepicker" data-date-format="yyyy-mm-dd" placeholder="Click to choose a date..." />
  			
    		<br />
			<?php if($readingCount > 1) : ?>
    			<div id="chart_div" style="width: 90%; height: 500px; margin: auto; border: 2px black solid;"></div>
			<?php else : ?>
				<div class="alert alert-danger" role="alert" style="width: 50%; margin: auto;">There is no data for this day :(</div>
			<?php endif; ?>
		</body>
	</html>