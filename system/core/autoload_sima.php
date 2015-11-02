<?php 
function core_loader($class_name)
{
	$file = CORE . '/' . strtolower($class_name) . '.php';
	if (file_exists($file)){
		require $file;
	}
}

function libs_loader($class_name)
{
	$file = LIBS . '/' . strtolower($class_name) . '_class.php';
	if (file_exists($file)){
		require $file;
	}
}

spl_autoload_register('core_loader');
spl_autoload_register('libs_loader');

//var_dump (spl_autoload_functions());

?>