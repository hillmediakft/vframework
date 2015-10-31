<?php
header('HTTP/1.0 404 Not Found');
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo BASE_URL;?>">
	<title>Hiba - Az oldal nem található!</title>
	<link href="<?php echo SITE_ASSETS;?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
</head>
<body style="background-color:#F8F8F8;">

	<div class="container">
		<div class="row">
			<div class="text-center" style="margin-top:100px;">
					  <h1 style="font-size:50px; color:#666666 ">Az oldal nem található <small style="color:red;">Hiba 404</small></h1>
					  <p class="lead" style="color:#BBBBBB;">Ellenőrizze, hogy helyesen írta-e be az oldal címét!</p>
					  <br />
					  <a class="btn btn-danger" href="<?php echo BASE_URL;?>">Vissza a kezdőoldalra</a>
			</div>
		</div>
	</div>
<!-- <script type="text/javascript" src="<?php //echo SITE_ASSETS;?>plugins/bootstrap/js/bootstrap.min.js"></script>-->
</body>
</html>
 