<?php
use System\Libs\LogIntoDb;
use System\Libs\Taxonomy;
//use System\Libs\Emailer;
//use System\Libs\DI;
//use System\Libs\Query;
//use System\Libs\Auth;
 
$config['events'] = array(

// USER
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

// TAXONOMY
    'insert_taxonomy' => function($content_id, $terms, $content_type) {
        $taxonomy = new Taxonomy();
        $taxonomy->insert($content_id, $terms, $content_type);
    },
    'update_taxonomy' => function($content_id, $terms, $content_type_id) {
        $taxonomy = new Taxonomy();
        $taxonomy->update($content_id, $terms, $content_type_id);
    },
    'delete_taxonomy' => function($content_id, $content_type_id) {
        $taxonomy = new Taxonomy();
        $taxonomy->delete($content_id, $content_type_id);
    }

);
?>