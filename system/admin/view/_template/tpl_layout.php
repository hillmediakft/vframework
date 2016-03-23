<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="hu">
<!--<![endif]-->

<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>V-Framework | <?php echo $this->title; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="<?php echo $this->description; ?>" name="description" />
    <meta content="" name="author" />
    <base href="<?php echo BASE_URL; ?>">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!-- <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" /> -->
    <link href="<?php echo ADMIN_ASSETS; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_ASSETS; ?>plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <?php $this->get_css_link(); ?>
    <!-- END PAGE LEVEL PLUGIN STYLES -->

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php echo ADMIN_CSS; ?>components.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo ADMIN_CSS; ?>plugins.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo ADMIN_CSS; ?>layout.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo ADMIN_CSS; ?>darkblue.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?php echo ADMIN_CSS; ?>custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="<?php echo ADMIN_IMAGE; ?>favicon.ico" />
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
    
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="index.html">
                    <img src="<?php echo ADMIN_IMAGE; ?>logo.png" alt="logo" class="logo-default" /> </a>
                <div class="menu-toggler sidebar-toggler"> </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte; -->
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="img-circle" src="<?php echo Config::get('user.upload_path') . Session::get('user_photo'); ?>" />
                            <span class="username username-hide-on-mobile"><?php echo Session::get('user_name'); ?></span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="admin/users/profile/<?php echo Session::get('user_id'); ?>">
                                    <i class="fa fa-user"></i> Profilom </a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="admin/login/logout">
                                    <i class="fa fa-key"></i> Kijelentkezés </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->


    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->
    
    

    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <?php $this->load('tpl_sidebar_menu'); ?>
        </div>
        <!-- END SIDEBAR WRAPPER -->
                                                            
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <?php $this->load('content'); ?>
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
                                                                                         
    </div>
    <!-- END CONTAINER -->                                                                                
                                                                                          

   
    <!-- BEGIN FOOTER -->
    <div class="page-footer">
        <div class="page-footer-inner">
             2015 &copy; V-Framework.
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
    <!-- END FOOTER -->

<!--[if lt IE 9]>
<script src="public/assets/global/plugins/respond.min.js"></script>
<script src="public/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo ADMIN_ASSETS;?>plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo ADMIN_ASSETS;?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- <script src="<?php //echo ADMIN_ASSETS;?>plugins/jquery.cokie.min.js" type="text/javascript"></script> -->
    <script src="<?php echo ADMIN_ASSETS;?>plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    
    <script src="<?php echo ADMIN_ASSETS;?>plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo ADMIN_ASSETS;?>plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo ADMIN_ASSETS;?>plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="<?php echo ADMIN_ASSETS;?>plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN  GLOBAL SCRIPTS -->
    <script src="<?php echo ADMIN_JS;?>app.js" type="text/javascript"></script>
    <script src="<?php echo ADMIN_JS;?>layout.js" type="text/javascript"></script>
    <script src="<?php echo ADMIN_JS;?>quick-sidebar.js" type="text/javascript"></script>
    <!-- END GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <?php $this->get_js_link(); ?>
    <!-- END PAGE LEVEL SCRIPTS -->
</body>
</html>