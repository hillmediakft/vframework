<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2015 &copy; V-Framework.
	</div>
	<div class="scroll-to-top">
		<i class="fa fa-arrow-circle-up"></i>
	</div>
</div>
	
	<!-- BEGIN CORE PLUGINS -->
	<!--[if lt IE 9]>
	<script src="<?php echo ADMIN_ASSETS;?>plugins/respond.min.js"></script>
	<script src="<?php echo ADMIN_ASSETS;?>plugins/excanvas.min.js"></script> 
	<![endif]-->	
	<script src="<?php echo ADMIN_ASSETS;?>plugins/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo ADMIN_ASSETS;?>plugins/jquery-migrate.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>    
	<script src="<?php echo ADMIN_ASSETS;?>plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo ADMIN_ASSETS;?>plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
 
	<script src="<?php echo ADMIN_ASSETS;?>plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
	<script src="<?php echo ADMIN_ASSETS;?>plugins/jquery.blockui.min.js" type="text/javascript"></script>  
	<script src="<?php echo ADMIN_ASSETS;?>plugins/jquery.cookie.min.js" type="text/javascript"></script>
	<script src="<?php echo ADMIN_ASSETS;?>plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
	<!-- END CORE PLUGINS -->
	
	<!-- BEGIN GLOBAL SCRIPTS -->
	<script src="<?php echo ADMIN_JS;?>metronic.js" type="text/javascript"></script>
	<script src="<?php echo ADMIN_JS;?>layout.js" type="text/javascript"></script>
    <script src="<?php echo ADMIN_JS;?>demo.js" type="text/javascript"></script> 
	<script src="<?php echo ADMIN_JS;?>quick-sidebar.js" type="text/javascript"></script>
	<!-- END GLOBAL SCRIPTS --> 
	
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<?php
		foreach($this->js_link as $value) {
			echo $value;
		} 
	?>
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>