<!DOCTYPE html>
<!--[if IE 8]> <html lang="hu" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="hu" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="hu" class="no-js">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>V-Framework | <?php echo $title; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="<?php echo $description; ?>" name="description" />
        <meta content="" name="author" />
        <base href="<?php echo BASE_URL; ?>">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->        
        <link href="<?php echo ADMIN_ASSETS; ?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ADMIN_ASSETS; ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ADMIN_ASSETS; ?>plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->

        <!-- OLDALSPECIFIKUS CSS LINKEK -->
        <?php foreach ($this->css_link as $value) {
            echo $value;
        } ?>


        <!-- END PAGE LEVEL PLUGIN STYLES -->
        <!-- BEGIN THEME STYLES -->
        <link href="<?php echo ADMIN_CSS; ?>components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ADMIN_CSS; ?>plugins.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ADMIN_CSS; ?>layout.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo ADMIN_CSS; ?>darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="<?php echo ADMIN_CSS; ?>custom.css" rel="stylesheet" type="text/css"/>
        <!-- END THEME STYLES -->
        <link rel="shortcut icon" href="<?php echo ADMIN_IMAGE; ?>favicon.ico" />

    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="page-header-fixed page-quick-sidebar-over-content page-style-square">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="admin/home">
                        <img src="<?php echo ADMIN_IMAGE; ?>logo.png" alt="logo" class="logo-default"/>
                    </a>
                    <div class="menu-toggler sidebar-toggler hide">
                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="<?php echo Config::get('user.upload_path') . Session::get('user_photo'); ?>"/>
                                <span class="username username-hide-on-mobile"><?php echo Session::get('user_name'); ?><i class="fa fa-angle-down"></i></span>

                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="admin/users/profile/<?php echo Session::get('user_id'); ?>">
                                        <i class="fa fa-user"></i> Profilom </a>
                                </li>

                                <li class="divider">
                                </li>
                                <!--						<li>
                                                                                        <a href="extra_lock.html">
                                                                                        <i class="fa fa-lock"></i> Képernyő zárolása </a>
                                                                                </li>  -->
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
        <div class="clearfix"></div>	


        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <div class="page-sidebar navbar-collapse collapse">        
                    <!-- BEGIN SIDEBAR MENU -->
                    <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                        <li class="sidebar-toggler-wrapper">
                            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                            <div class="sidebar-toggler">
                            </div>
                            <!-- END SIDEBAR TOGGLER BUTTON -->
                        </li>

                        <li class="<?php echo ($this->request->get_controller() == 'home') ? 'active' : ''; ?>">
                            <a href="admin/home">
                                <i class="fa fa-home"></i> 
                                <span class="title">Kezdőoldal</span>
                                <span class="selected"></span>
                            </a>
                        </li>

                        <!-- SZERKESZTHETŐ OLDALAK -->
                        <li class="<?php echo ($this->request->get_controller() == 'pages' || $this->request->get_controller() == 'content') ? 'active' : ''; ?>">
                            <a href="javascript:;">
                                <i class="fa fa-files-o"></i> 
                                <span class="title">Oldalak</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">


                                <li class="<?php echo ($this->request->get_controller() == 'pages') ? 'active' : ''; ?>">
                                    <a href="admin/pages">Oldalak listája</a>
                                </li>


                            </ul>
                        </li>

                        <!-- ADMIN USERS -->	
                        <li class="<?php echo ($this->request->get_controller() == 'users') ? 'active' : ''; ?>">
                            <a href="javascript:;">
                                <i class="fa fa-users"></i> 
                                <span class="title">Felhasználók</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="<?php echo ($this->request->get_action() == 'index') ? 'active' : ''; ?>">
                                    <a href="admin/users">
                                        Felhasználók listája</a>
                                </li>

<?php if (Session::get('user_role_id') == 1) { ?>
                                    <li class="<?php echo ($this->request->get_action() == 'new_user') ? 'active' : ''; ?>">
                                        <a href="admin/users/new_user">
                                            Új felhasználó</a>
                                    </li>
<?php } ?>

                                <li class="<?php echo ($this->request->get_action() == 'profile') ? 'active' : ''; ?>">
                                    <a href="admin/users/profile/<?php echo Session::get('user_id'); ?>">
                                        Profilom</a>
                                </li>
                                <!-- FELHASZNÁLÓI CSOPORTOK						
                                                                                <li class="<?php //echo ($this->request->get_action() == 'user_roles' || $this->request->get_action() == 'edit_roles') ? 'active' : ''; ?>">
                                                                                        <a href="admin/users/user_roles">
                                                                                        Csoportok</a>
                                                                                </li>
                                -->
                            </ul>
                        </li>



                        <!--  GALÉRIÁK
                                                        <li class="<?php //echo ($this->request->get_controller() == 'photo_gallery' || $this->request->get_controller() == 'video_gallery') ? 'active' : ''; ?>">
                                                                <a href="javascript:;">
                                                                <i class="fa fa-picture-o"></i> 
                                                                <span class="title">Galériák</span>
                                                                <span class="arrow "></span>
                                                                </a>
                                                                <ul class="sub-menu">
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'photo_gallery') ? 'active' : ''; ?>">
                                                                                <a href="admin/photo_gallery">
                                                                                Képgaléria</a>
                                                                        </li>
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'video_gallery') ? 'active' : ''; ?>">
                                                                                <a href="admin/video_gallery">
                                                                                Videógaléra</a>
                                                                        </li>
                                                                </ul>
                                                        </li>
                        -->				
                        <li class="<?php echo ($this->request->get_controller() == 'slider' || $this->request->get_controller() == 'testimonials') ? 'active' : ''; ?>">
                            <a href="javascript:;">
                                <i class="fa fa-suitcase"></i> 
                                <span class="title">Modulok</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="<?php echo ($this->request->get_controller() == 'slider') ? 'active' : ''; ?>">
                                    <a href="admin/slider">
                                        Slider beállítások</a>
                                </li>
                                <li class="<?php echo ($this->request->get_controller() == 'testimonials') ? 'active' : ''; ?>">
                                    <a href="admin/testimonials">
                                        Rólunk mondták</a>
                                </li>
                            </ul>

                        </li>
                        <li class="<?php echo ($this->request->get_controller() == 'file_manager') ? 'active' : ''; ?>">
                            <a href="admin/file_manager">
                                <i class="fa fa-folder-open-o"></i> 
                                <span class="title">Fájlkezelő</span>
                            </a>

                        </li>

                        <!-- ALAP BEÁLLÍTÁSOK -->	
                        <li class="<?php echo ($this->request->get_controller() == 'settings') ? 'active' : ''; ?>">
                            <a href="javascript:;">
                                <i class="fa fa-cogs"></i> 
                                <span class="title">Beállítások</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="<?php echo ($this->request->get_controller() == 'settings') ? 'active' : ''; ?>">
                                    <a href="admin/settings">
                                        Oldal szintű beállítások</a>
                                </li>
                            </ul>
                        </li>

                        <li class="<?php echo ($this->request->get_controller() == 'user_manual') ? 'active' : ''; ?>">
                            <a href="admin/user-manual">
                                <i class="fa fa-file-text-o"></i> 
                                <span class="title">Dokumentáció</span>
                            </a>
                        </li>                                

                        <!--  NYELVEK				
                                                        <li class="<?php //echo ($this->request->get_controller() == 'languages') ? 'active' : ''; ?>">
                                                                <a href="admin/languages">
                                                                <i class="fa fa-globe"></i> 
                                                                <span class="title">Nyelvek</span>
                                                                </a>
                                                        </li>
                        -->				
                        <!-- HÍRLEVÉL				
                                                        <li class="<?php //echo ($this->request->get_controller() == 'newsletter') ? 'active' : ''; ?>">
                                                                <a href="javascript:;">
                                                                        <i class="fa fa-suitcase"></i> 
                                                                        <span class="title">Hírlevél</span>
                                                                        <span class="arrow "></span>
                                                                </a>
                                                                <ul class="sub-menu">
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'newsletter' && $this->request->get_action() == 'index') ? 'active' : ''; ?>">
                                                                                <a href="admin/newsletter">Hírlevelek</a>
                                                                        </li>
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'newsletter' && $this->request->get_action() == 'new_newsletter') ? 'active' : ''; ?>">
                                                                                <a href="admin/newsletter/new_newsletter">Új hírlevél</a>
                                                                        </li>
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'newsletter' && $this->request->get_action() == 'new_newsletter') ? 'active' : ''; ?>">
                                                                                <a href="admin/newsletter/newsletter_stats">Elküldött hírlevelek</a>
                                                                        </li>						
                                                                </ul>
                                                        </li>
                                                        
                                                        <li class="last <?php //echo ($this->request->get_controller() == 'blog') ? 'active' : ''; ?>">
                                                                <a href="javascript:;">
                                                                        <i class="fa fa-suitcase"></i> 
                                                                        <span class="title">Blog</span>
                                                                        <span class="arrow "></span>
                                                                </a>
                                                                <ul class="sub-menu">
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'blog' && $this->request->get_action() == 'index') ? 'active' : ''; ?>">
                                                                                <a href="admin/blog">Bejegyzések</a>
                                                                        </li>
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'blog' && $this->request->get_action() == 'new_blog') ? 'active' : ''; ?>">
                                                                                <a href="admin/blog/insert">Új bejegyzés</a>
                                                                        </li>
                                                                        <li class="<?php //echo ($this->request->get_controller() == 'blog' && $this->request->get_action() == 'category') ? 'active' : ''; ?>">
                                                                                <a href="admin/blog/category">Kategóriák</a>
                                                                        </li>
                                                                </ul>
                                                        </li>				
                        -->				

                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!--END PAGE SIDEBAR WRAPPER -->