<?php
require_once("common.php");
    if (!isset($loginSession))
        doUnauthenticatedRedirect();
        
$user = $db->readSensors();
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
			<style type="text/css">
			input { max-width: 100%; }
			</style>
			<script type="text/javascript">

			
			</script>
		</head>
 
		<body>
			<?php var_dump($user); ?>
			<!-- JavaScript -->
			<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script src="js/bootstrap.js"></script>
		</body>
	</html>
	