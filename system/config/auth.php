<?php 
$config['auth'] = array(

	// DB table name for the user table
	'table_name' => 'users',
	//Choose which columns are selected
	'table_columns' => array('*'),
	//'guest_login' => true,

	/**
	 * Remember-me functionality
	 */
	'remember_me' => array(
		// Whether or not remember me functionality is enabled
		'enabled' => false,
		//Name of the cookie used to record this functionality
		'cookie_name' => 'tesztcookie',
		// Remember me expiration (default: 31 days)
		'expiration' => 86400 * 31
	),

	/**
	 * A user tábla username oszlop neve
	 */
	'username_colname' => 'name',
	/**
	 * A user tábla username oszlop neve
	 */
	'email_colname' => 'email',
	/**
	 * A user tábla password oszlop neve
	 */
	'password_colname' => 'password_hash',

	/**
	 * Munkamenet lejárati ideje
	 * 18000 = 5 óra, 14400 = 4 óra, 10800 = 3 óra, 7200 = 2 óra, 3600 = 1 óra
	 */
	'session_expire_time' => 18000,
);
?>