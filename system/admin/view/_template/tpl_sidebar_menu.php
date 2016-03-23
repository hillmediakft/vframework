<!-- BEGIN SIDEBAR -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<div class="page-sidebar navbar-collapse collapse">
    <!-- BEGIN SIDEBAR MENU -->
    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="100" style="padding-top: 20px">
        <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
        <li class="sidebar-toggler-wrapper hide">
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler"> </div>
            <!-- END SIDEBAR TOGGLER BUTTON -->
        </li>

    <!-- BEGIN MENU ITEMS -->
        
        <!-- KEZDŐOLDAL -->
        <li class="nav-item start <?php $this->menu_active('home'); ?> ">
            <a href="admin/home" class="nav-link ">
                <i class="fa fa-home"></i>
                <span class="title">Kezdőoldal</span>
            </a>
        </li>

        <!-- SZERKESZTHETŐ OLDALAK -->
        <li class="nav-item <?php $this->menu_active('pages|content'); ?> ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-files-o"></i>
                <span class="title">Oldalak</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item <?php $this->menu_active('pages'); ?> ">
                    <a href="admin/pages" class="nav-link ">
                        <span class="title">Oldalak listája</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- ADMIN USERS -->
        <li class="nav-item <?php $this->menu_active('users'); ?> ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-users"></i>
                <span class="title">Felhasználók</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item <?php $this->menu_active('users', 'index'); ?> ">
                    <a href="admin/users" class="nav-link ">
                        <span class="title">Felhasználók listája</span>
                    </a>
                </li>
                <?php if (1) { ?>
                <li class="nav-item <?php $this->menu_active('users', 'insert'); ?> ">
                    <a href="admin/users/insert" class="nav-link ">
                        <span class="title">Új felhasználó</span>
                    </a>
                </li>
                <?php } ?>
                <li class="nav-item <?php $this->menu_active('users', 'profile'); ?> ">
                    <a href="admin/users/profile/<?php echo Session::get('user_id'); ?>" class="nav-link ">
                        <span class="title">Profilom</span>
                    </a>
                </li>
                <li class="nav-item <?php $this->menu_active('users', 'user_roles|edit_roles'); ?> ">
                    <a href="admin/users/user_roles" class="nav-link ">
                        <span class="title">Csoportok</span>
                    </a>
                </li>
            </ul>
        </li>

        <!--  GALÉRIÁK -->
        <li class="nav-item <?php $this->menu_active('photo_gallery|video_gallery'); ?> ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-picture-o"></i>
                <span class="title">Galériák</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item <?php $this->menu_active('photo_gallery', 'index'); ?> ">
                    <a href="admin/photo_gallery" class="nav-link ">
                        <span class="title">Képgaléria</span>
                    </a>
                </li>
                <li class="nav-item <?php $this->menu_active('photo_gallery', 'category'); ?> ">
                    <a href="admin/photo_gallery/category" class="nav-link ">
                        <span class="title">Képgaléria kategóriák</span>
                    </a>
                </li>
                <li class="nav-item <?php $this->menu_active('video_gallery'); ?> ">
                    <a href="admin/video_gallery" class="nav-link ">
                        <span class="title">Videógaléra</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- MODULOK -->
        <li class="nav-item <?php $this->menu_active('slider|testimonials|clients'); ?> ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-suitcase"></i>
                <span class="title">Modulok</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <!-- SLIDER -->
                <li class="nav-item <?php $this->menu_active('slider'); ?> ">
                    <a href="admin/slider" class="nav-link ">
                        <span class="title">Slider beállítások</span>
                    </a>
                </li>
                <!-- RÓLUNK MONDTÁK --> 
                <li class="nav-item <?php $this->menu_active('testimonials'); ?> ">
                    <a href="admin/testimonials" class="nav-link ">
                        <span class="title">Rólunk mondták</span>
                    </a>
                </li>
                <!-- PARTNEREK -->
                <li class="nav-item <?php $this->menu_active('clients'); ?> ">
                    <a href="admin/clients" class="nav-link ">
                        <span class="title">Partnerek</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- FILE-KEZELŐ -->
        <li class="nav-item <?php $this->menu_active('file_manager'); ?> ">
            <a href="admin/file_manager" class="nav-link ">
                <i class="fa fa-folder-open-o"></i>
                <span class="title">Fájlkezelő</span>
            </a>
        </li>

        <!-- ALAP BEÁLLÍTÁSOK -->
        <li class="nav-item <?php $this->menu_active('settings'); ?> ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-cogs"></i>
                <span class="title">Beállítások</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item <?php $this->menu_active('settings'); ?> ">
                    <a href="admin/settings" class="nav-link ">
                        <span class="title">Oldal szintű beállítások</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- DOKUMENTÁCIÓ -->
        <li class="nav-item <?php $this->menu_active('user_manual'); ?> ">
            <a href="admin/user-manual" class="nav-link ">
                <i class="fa fa-file-text-o"></i>
                <span class="title">Dokumentáció</span>
            </a>
        </li>

        <!-- NELVEK -->
        <!--
        <li class="nav-item <?php //$this->menu_active('languages'); ?> ">
            <a href="admin/languages" class="nav-link ">
                <i class="fa fa-globe"></i>
                <span class="title">Nyelvek</span>
            </a>
        </li>
        -->

        <!-- HÍRLEVÉL -->
        <li class="nav-item <?php $this->menu_active('newsletter'); ?> ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-suitcase"></i>
                <span class="title">Hírlevél</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item <?php $this->menu_active('newsletter', 'index'); ?> ">
                    <a href="admin/newsletter" class="nav-link ">
                        <span class="title">Hírlevelek</span>
                    </a>
                </li>
                <li class="nav-item <?php $this->menu_active('newsletter', 'insert'); ?> ">
                    <a href="admin/newsletter/insert" class="nav-link ">
                        <span class="title">Új hírlevél</span>
                    </a>
                </li>
                <li class="nav-item <?php $this->menu_active('newsletter', 'newsletter_stats'); ?> ">
                    <a href="admin/newsletter/newsletter_stats" class="nav-link ">
                        <span class="title">Elküldött hírlevelek</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- BLOG -->
        <li class="nav-item <?php $this->menu_active('blog'); ?> ">
            <a href="javascript:;" class="nav-link nav-toggle">
                <i class="fa fa-suitcase"></i>
                <span class="title">Blog</span>
                <span class="arrow"></span>
            </a>
            <ul class="sub-menu">
                <li class="nav-item <?php $this->menu_active('blog', 'index'); ?> ">
                    <a href="admin/blog" class="nav-link ">
                        <span class="title">Bejegyzések</span>
                    </a>
                </li>
                <li class="nav-item <?php $this->menu_active('blog', 'insert'); ?> ">
                    <a href="admin/blog/insert" class="nav-link ">
                        <span class="title">Új bejegyzés</span>
                    </a>
                </li>
                <li class="nav-item <?php $this->menu_active('blog', 'category'); ?> ">
                    <a href="admin/blog/category" class="nav-link ">
                        <span class="title">Kategóriák</span>
                    </a>
                </li>
            </ul>
        </li>
    <!-- END MENU ITEMS -->

    </ul>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->