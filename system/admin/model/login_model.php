<?php
namespace System\Admin\Model;
use System\Core\Admin_model;

/**
 * LoginModel
 *
 * Handles the user's login / logout / registration stuff
 */
class Login_model extends Admin_model
{
    /**
     * Constructor, létrehozza az adatbáziskapcsolatot
     */
    public function __construct()
    {
		parent::__construct();
    }

    /**
     * Gets the last page the user visited
     * writeUrlCookie() in libs/Application.php writes the URL of the user's page location into the cookie at every
     * page request. This is useful to redirect the user (after login via cookie) back to the last seen page before
     * his/her session expired or he/she closed the browser
     * @return string view/location the user visited
     */
    public function getCookieUrl()
    {
        return (!empty($_COOKIE['lastvisitedpage'])) ? $_COOKIE['lastvisitedpage'] : '';
    }

} //end class
?>