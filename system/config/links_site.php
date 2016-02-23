<?php 
$link['select2'] = array(
	'css' => ADMIN_ASSETS . 'plugins/select2/select2.css',
	'js' => ADMIN_ASSETS . 'plugins/select2/select2.min.js'
);

$link['datatable'] = array(
	'css' => ADMIN_ASSETS . 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
	'js' => array(
		ADMIN_ASSETS . 'plugins/datatables/media/js/jquery.dataTables.min.js',
		ADMIN_ASSETS . 'plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js',
		ADMIN_JS . 'datatable.js'
	)
);

$link['bootbox'] = array(
	'js' => ADMIN_ASSETS . 'plugins/bootbox/bootbox.min.js'
);

$link['datepicker'] = array(
	'css' => ADMIN_ASSETS . 'plugins/bootstrap-datepicker/css/datepicker.css',
	'js' => array(
		ADMIN_ASSETS . 'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
		ADMIN_ASSETS . 'plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.hu.js'
	)
);

$link['bootstrap-fileupload'] = array(
	'css' => ADMIN_ASSETS . 'plugins/bootstrap-fileupload/bootstrap-fileupload.css',
	'js' => ADMIN_ASSETS . 'plugins/bootstrap-fileupload/bootstrap-fileupload.js'
);

$link['ckeditor'] = array(
	'js' => ADMIN_ASSETS . 'plugins/ckeditor/ckeditor.js'
);

return $link;
?>