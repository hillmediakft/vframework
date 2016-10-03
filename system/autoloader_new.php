<?php
spl_autoload_register('NewAutoload');
// a fully qualified classname-t kapja meg
function NewAutoload($className) {
    $segments = explode("\\", $className);
    if (file_exists(APP_ROOT. implode(DS, $segments) . ".php" )) {
       include_once(APP_ROOT. implode(DS, $segments). ".php"); 
    }
}
?>