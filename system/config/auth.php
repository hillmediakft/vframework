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
	 * Átirányítás helye: pl.: login (ilyenkor a site_url/login oldalra fog irányítani)
	 * Ez lesz az alapbeállítás, ha a handleExpire(), vagy handleLogin() metódust paraméter nélkül hívjuk meg
	 */
	'target_url' => 'login',

	/**
	 * Munkamenet lejárati ideje
	 */
	'session_expire_time' => 3600,
);
?>