<?php
namespace System\Libs;

/**
 * Hooks Class
 *
 * Provides a mechanism to extend the base system without hacking.
 *
 * @package     V-Framework
 */
class Hooks {

    /**
     * List of all hooks set in config/hooks.php
     *
     * @var array
     */
    var $hooks = array();

    /**
     * Determines wether hook is in progress, used to prevent infinte loops
     *
     * @var bool
     */
    var $in_progress = FALSE;

    /**
     * Constructor
     *
     */
    function __construct() {
        $this->_initialize();
    }

    // --------------------------------------------------------------------

    /**
     * Initialize the Hooks Preferences
     *
     * @access	private
     * @return	void
     */
    function _initialize() {
        // Grab the "hooks" definition file.
        // If there are no hooks, we're done.

        Config::load('hooks');
        $hooks_array = Config::get('hook');
        if (!isset($hooks_array) OR !is_array($hooks_array)) {
            return;
        }
        $this->hooks = $hooks_array;
    }

    // --------------------------------------------------------------------

    /**
     * Call Hook
     *
     * Calls a particular hook
     *
     * @access	private
     * @param	string	the hook name
     * @return	mixed
     */
    function _call_hook($which = '') {
        if (! isset($this->hooks[$which])) {
            return FALSE;
        }

        if (isset($this->hooks[$which][0]) AND is_array($this->hooks[$which][0])) {
            foreach ($this->hooks[$which] as $val) {
                $this->_run_hook($val);
            }
        } else {
            $this->_run_hook($this->hooks[$which]);
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Run Hook
     *
     * Runs a particular hook
     *
     * @access	private
     * @param	array	the hook details
     * @return	bool
     */
    function _run_hook($data) {
        if (!is_array($data)) {
            return FALSE;
        }

        // -----------------------------------
        // Safety - Prevents run-away loops
        // -----------------------------------
        // If the script being called happens to have the same
        // hook call within it a loop can happen

        if ($this->in_progress == TRUE) {
            return;
        }

        // -----------------------------------
        // Set file path
        // -----------------------------------

        if (!isset($data['filepath']) OR ! isset($data['filename'])) {
            return FALSE;
        }

        $filepath = $data['filepath'] . '/' . $data['filename'];

        if (!file_exists($filepath)) {
            return FALSE;
        }

        // -----------------------------------
        // Set class/function name
        // -----------------------------------

        $class = FALSE;
        $function = FALSE;
        $params = '';

        if (isset($data['class']) AND $data['class'] != '') {
            $class = $data['class'];
        }

        if (isset($data['function'])) {
            $function = $data['function'];
        }

        if (isset($data['params'])) {
            $params = $data['params'];
        }

        if ($class === FALSE AND $function === FALSE) {
            return FALSE;
        }

        // -----------------------------------
        // Set the in_progress flag
        // -----------------------------------

        $this->in_progress = TRUE;

        // -----------------------------------
        // Call the requested class and/or function
        // -----------------------------------

        if ($class !== FALSE) {
            if (!class_exists($class)) {
                require($filepath);
            }

            $HOOK = new $class;
            $HOOK->$function($params);
        } else {
            if (!function_exists($function)) {
                require($filepath);
            }

            $function($params);
        }

        $this->in_progress = FALSE;
        return TRUE;
    }

}

// END Hooks class