<?php
class db{

	/*** Declare connect ***/
	public static $connect = null;

	/**
	 * the constructor is set to private so
	 * so nobody can create a new connect using new
	 */
	private function __construct() {
	  /*** maybe set the db name here later ***/
	}

	/**
	 * Visszaad egy PDO adatbázis kapcsolat objektumot (hozzárendeli a $connect statikus tulajdonsághoz),
	 * vagy létrehoz egy új adatbázis kapcsolatot (ha nincs), és azt adja vissza
	 *
	 * @return object (PDO)
	 * @access public
	 */
	public static function get_connect() {

		if (!self::$connect) {
			try {
				self::$connect = new PDO('mysql:host=' . Config::get('db.host') . ';dbname=' . Config::get('db.name') . ';charset=utf8', Config::get('db.user'), Config::get('db.pass'));
				self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e) {
				die('Database error: ' . $e->getMessage());
			}
		}
	return self::$connect;
	}

	/**
	 *	Lezárja az adatbázis kapcsolatot (A PDO objektum-hoz a null értéket rendeli hozzá)
	 */
	public static function close_connect()
	{
		if(self::$connect != null){
			self::$connect = null;
		}
		return self::$connect;
	}

    /**
     * Like the constructor, we make __clone private
     * so nobody can clone the connect
     */
    private function __clone(){ }

} /*** end of class ***/
?>