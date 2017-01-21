<!DOCTYPE html>
<!--[if IE 8]> <html lang="hu" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="hu" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="hu"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8">
	<title>V-Framework | Bejelentkezés</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<base href="<?php echo BASE_URL;?>">
	<link href="<?php echo ADMIN_ASSETS;?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> 
	<link href="<?php echo ADMIN_CSS;?>pages/login.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="<?php echo ADMIN_IMAGE;?>favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body>
<div class="login">
	<h1>Admin bejelentkezés</h1>
	
        <div>
            <!-- echo out the system feedback (error and success messages) -->
            <?php $this->renderFeedbackMessages();?>
        </div>

        <!-- login form box -->
        <form method="post" action="<?php echo BASE_URL . 'admin/login';?>" name="loginform">
            <div class="form-group" style="margin-bottom:8px;">	
                <label for="login_input_username">Felhasználónév</label>
                <input id="login_input_username" class="form-control" type="text" name="user_name">
            </div>
            <div class="form-group" style="margin-bottom:8px;">
                <label for="login_input_password">Jelszó</label>
                <input id="login_input_password" class="form-control" type="password" name="user_password" autocomplete="off">
            </div>

            <div class="form-group" style="margin-bottom:15px;">

                <input class="remember-me-checkbox" type="checkbox" name="user_rememberme">
                Emlékezz rám
            </div>


            <div class="form-group text-center">
                <input class="btn btn-info" type="submit"  name="submit_login" value="Bejelentkezés" />
            </div>
        </form>

</div>
</body>
<!-- END BODY -->
</html>