<?php
require_once("common.php");
if (isset($loginSession))
    doLoginRedirect($loginSession, @$_SERVER['PATH_INFO']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<base href="<?php echo htmlentities(BASE_URL); ?>">
			<title>Add</title>
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

			function addUser(form) {
				var username = requiredField($(form.elements.username), "You must enter a username");
				var password1 = requiredField($(form.elements.password1), "You must enter a password");
				var password2 = requiredField($(form.elements.password2), "You must re-enter password to verify");
				var email = requiredField($(form.elements.email), "You must enter an email");
				var device = requiredField($(form.elements.device), "You must enter a device");
				if ((username == "") || (password1 == "") || (email == "") || (device == "")) {
					showError("You must enter username, password, email, and device information.");
					return false;
				}
				
				if ((password1 != password2)) {
					showError("Passwords do not match. Please re-enter.");
					return false;
				}
				
				$("#loginDiv").hide();
				$("#spinner").show();

				$.ajax({
					"type" : "POST",
					"url" : "doAdd.php",
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
						$("#signUpDiv").show();
						showError("Request failed, unable to add new user: "+ errorThrown);
					})
				return false;
			} // addUser
		</script>
		</head>
 
		<body>
			<div class="navbar navbar-inverse navbar-static-top">
				<div class="container">
					<a href="#" class="navbar-brand">
						<span class="glyphicon glyphicon-cloud"></span>
						Air Quality
					</a>
					<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="collapse navbar-collapse navHeaderCollapse">
						<ul class="nav navbar-nav navbar-right">
						</ul>
					</div>
				</div>
			</div>

			<div id="messageAlert" class="alert alert-danger" style="display:none;position:fixed;left:20px;right:20px">
				<span id="message"></span>
			</div>

			<div class="container padded">
				<div class="row" >
					<div id="spinner" class="col-md-2 col-md-offset-5" style="padding-bottom:10px;text-align:center;display:none">
						<div style="color:black;padding-bottom:32px;">Adding you to our Records...</div>
						<img src="spinner.gif">
					</div>
					<div id="successDiv" style="padding:10px; outline:10px solid black; display:none">
        				You have been added. Go to login by clicking the Air Quality link on the top left.
    				</div>
					<div id="signUpDiv" class="col-md-3 col-md-offset-5" style="padding-bottom:10px; outline: 10px solid black;">
						<form class="form-horizontal" method="post" onsubmit="return addUser(this)">
							<fieldset>
								<legend style="color:black;">Sign Up</legend>
								<div class="control-group">
									<label class="control-label" for="username">Username</label>
									<div class="controls">
										<input name="username" maxlength="50" placeholder="Enter your username..." type="text" class="form-control" id="username" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="email">Email</label>
									<div class="controls">
										<input name="email" maxlength="50" placeholder="Enter an email..." type="email" class="form-control" id="email" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="device">Device</label>
									<div class="controls">
										<input name="device" maxlength="50" placeholder="Enter a device..." type="text" class="form-control" id="device" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="password1">Password</label>
									<div class="controls">
										<input name="password1" maxlength="50" placeholder="Enter your password..." type="password" class="form-control" id="password1" />
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="password2">Re-Enter Password</label>
									<div class="controls">
										<input name="password2" maxlength="50" placeholder="Re-Enter your password..." type="password" class="form-control" id="password2" />
									</div>
								</div>
								</br>
								<div>
									<input class="btn btn-primary" name="commit" type="submit" value="Sign Up" />
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<!-- JavaScript -->
			<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.js"></script>
			<script>
				$('#username').focus();
			</script>
		</body>
	</html>
	
