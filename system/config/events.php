<?php
use System\Libs\LogIntoDb;
use System\Libs\Emailer;
use System\Libs\DI;
use System\Libs\Query;
use System\Libs\Auth;
 
$config['events'] = array(

	'insert_user' => function($type, $message){

		$log = new LogIntoDb();
		$log->index($type, $message);

	},
	'update_user' => function($type, $message){

		$log = new LogIntoDb();
		$log->index($type, $message);

	},
	'delete_user' => function($type, $message){

		$log = new LogIntoDb();
		$log->index($type, $message);

	},
);
?>