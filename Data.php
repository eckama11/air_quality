<?php
require_once("common.php");
    if (!isset($loginSession))
        doUnauthenticatedRedirect();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<base href="<?php echo htmlentities(BASE_URL); ?>">
			<title>Login</title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<!-- StyleSheet -->
			<link rel="stylesheet" href="css/bootstrap.min.css" />
			<link rel="stylesheet" href="css/custom.css" />
			<style>
			input { max-width: 100%; }
			</style>
			<script>
			function requiredField(elem, errorMsg) {
				var rv = elem.val();
				if (rv == "") {
					elem.tooltip("destroy")
						.addClass("error")
						.data("title", errorMsg)
						.tooltip();
				} else {
					elem.tooltip("destroy")
						.removeClass("error")
						.data("title", "");
				}
				return rv;
			}

			function showError(message) {
				$("#message").text(message);
				$("#messageAlert").show().delay(3000).fadeOut("slow");
			}

			function addData(form) {
				var id = requiredField($(form.elements.id), "You must enter an id");
				var imp = requiredField($(form.elements.imp), "You must enter an imp");
				var time = requiredField($(form.elements.time), "You must enter a time");
				var temp = requiredField($(form.elements.newTemp), "You must enter an temperature");
				var humidity = requiredField($(form.elements.newHum), "You must enter an humidity");
				var pressure = requiredField($(form.elements.pressure), "You must enter a pressure");
				var altitude = requiredField($(form.elements.altitude), "You must enter a altitude");
				var latitude = requiredField($(form.elements.latitude), "You must enter a latitude");
				var longitude = requiredField($(form.elements.longitude), "You must enter a longitude");
				var particles = requiredField($(form.elements.particles), "You must enter a particles");
				if ((id == "") || (imp == "") || (time == "") || (temp == "") || (humidity == "") 
				|| (pressure == "") || (altitude == "") || (latitude == "") || (longitude == "") || (particles == "")) {
					showError("You must enter id, imp, time, temp, humidity, pressure, altitude, latitude, longitude, particles information.");
					return false;
				}
				
				$("#loginDiv").hide();
				$("#spinner").show();
 
				$.ajax({
					"type" : "POST",
					"url" : "doData.php",
					"data" : $(form).serialize(),
					"dataType" : "json"
					})
					.done(function(data) {
            			$("#spinner").hide();
						if (data.error != null) {
                			showError(data.error);
                			$("#content").show();
            			} else
                			$("#successDiv").show();
        			})
					.fail(function( jqXHR, textStatus, errorThrown ) {
						console.log("Error: "+ textStatus +" (errorThrown="+ errorThrown +")");
						console.log(jqXHR);
						$("#spinner").hide();
						$("#loginDiv").show();
						showError("Request failed, unable to add data: "+ errorThrown);
					})
				return false;
			} // doLogin
			</script>
		</head>
 
		<body>
			<div id="messageAlert" class="alert alert-danger" style="display:none;position:fixed;left:20px;right:20px">
				<span id="message"></span>
			</div>

			<div class="container padded">
				<div class="row" >
					<div id="spinner" class="col-md-2 col-md-offset-5" style="padding-bottom:10px;text-align:center;display:none">
						<div style="color:black;padding-bottom:32px;">Adding reading to our Records...</div>
						<img src="spinner.gif">
					</div>
					<div id="successDiv" style="padding:10px; outline:10px solid black; display:none">
        				Your reading has been added. Thank you.
    				</div>
					<div id="dataDiv" class="col-md-3 col-md-offset-5" style="padding-bottom:10px; outline: 10px solid black;">
						<form class="form-horizontal" method="post" onsubmit="return addData(this)">
							<fieldset>
								<legend style="color:black;">Add Reading</legend>
								<div class="control-group">
									<label class="control-label" for="id">Id</label>
									<div class="controls">
										<input name="id" maxlength="50" placeholder="Enter your id..." type="text" class="form-control" id="id" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="imp">Imp</label>
									<div class="controls">
										<input name="imp" maxlength="50" placeholder="Enter an imp..." type="text" class="form-control" id="imp" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="newTemp">Temperature</label>
									<div class="controls">
										<input name="newTemp" maxlength="50" placeholder="Enter a temperature..." type="text" class="form-control" id="newTemp" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="newHum">Humidity</label>
									<div class="controls">
										<input name="newHum" maxlength="50" placeholder="Enter a humidity..." type="text" class="form-control" id="newHum" />
									</div>
								</div>
									
								<div class="control-group">
									<label class="control-label" for="pressureA">Pressure</label>
									<div class="controls">
										<input name="pressureA" maxlength="50" placeholder="Enter a pressure..." type="text" class="form-control" id="pressureA" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="altitude">Altitude</label>
									<div class="controls">
										<input name="altitude" maxlength="50" placeholder="Enter your altitude..." type="text" class="form-control" id="altitude" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="latitude">Latitude</label>
									<div class="controls">
										<input name="latitude" maxlength="50" placeholder="Enter your latitude..." type="text" class="form-control" id="latitude" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="longitude">Longitude</label>
									<div class="controls">
										<input name="longitude" maxlength="50" placeholder="Enter your longitide..." type="text" class="form-control" id="longitude" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="particles">Particles</label>
									<div class="controls">
										<input name="particles" maxlength="50" placeholder="Enter your particles..." type="text" class="form-control" id="particles" />
									</div>
								</div>
								</br>
								<div>
									<button type="submit" class="btn btn-default">Submit</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<!-- JavaScript -->
			<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.js"></script>
		</body>
	</html>
	