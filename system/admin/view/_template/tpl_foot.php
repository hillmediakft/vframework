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