<?php
require_once("common.php");
    if (!isset($loginSession))
        doUnauthenticatedRedirect();
        
//$sensor = $db->readSensors();
$device = $loginSession->authenticatedUser->deviceId;
if(isset($_POST['calDate'])){
	$date = date('m/d/Y',$_POST['calDate']); //Use posted date
}else{
	$date = date('m/d/Y'); //Default to today
}
$js_array = $db->readHumidity($date,$device);
$readingCount = count($js_array);
?>
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
    		
    	</head>
		<body>
		
		<form method="post">
    		<input name='calDate' type="text" class="form-control" id="datepicker" style="display: block; text-align:center; width: 20%; margin-left: auto; margin-right: auto; border: 2px black solid;" 
    		data-provide="datepicker" placeholder="Click to choose a date..." />
  			<script type="text/javascript">
				// Init date picker and display UI
				$('#datepicker').datepicker({
					format: "mm/dd/yyyy",
					clearBtn: true,
					todayBtn: "linked",
					autoclose: true,
					endDate: "<?php echo $date;?>",
					todayHighlight: true,
					forceParse: false
				}).on('changeDate', function(){
					$(this).parent('form').submit();
				});
			</script>
		</form>
    		<br />
			<?php if($readingCount > 1) : ?>
    			<div id="chart_div" style="width: 90%; height: 500px; margin: auto; border: 2px black solid;"></div>
			<?php else : ?>
				<div class="alert alert-danger" role="alert" style="width: 50%; margin: auto;">There is no data for this day :(</div>
			<?php endif; ?>
		</body>
	</html>