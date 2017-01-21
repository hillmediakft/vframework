<?php
namespace System\Libs;

/**
* Események betöltése és regisztrálása config fileból
*   Event_manager::init('config_file_name'); 
*
* Register event as an anonymous function
*   Event_maganer::register('event_name', function($arg1, $arg2) { echo $arg1; echo $arg2; });
*
*   $callback = function($arg1, $arg2) { echo $arg1; echo $arg2; };
*   Event_maganer::register('event_name', $callback);
*
* Unregister all subscribers
*   Event_manager::clear();
*
* Unregister all subscribers based on event name
*   Event_manager::unregister('event_name');
*
* Unregister one single subscriber
*   Event_manager::unregister('event_name', $callback);
*   Event_manager::unregister('event_name', Config::get('events.user_update'));
*
* Get all events and subscribers
*   Event_manager::getEvents();
*
* Check if event is registered
*   Event_manager::has('event_name');
*
* Check if subscriber is registered
*   Event_manager::has('event_name', $callback);
*   Event_manager::has('event_name', Config::get('events.user_update'));
*
* Trigger event with arguments
*   Event_manager::trigger('event_name', array('param1', 'param2', 123, 'param4');
*
* Trigger event without arguments
*   Event_manager::trigger('event_name');
*/
class EventManager
{
    /**
     * @var array
     */
    private static $events = array();
    
    private function __construct() {}
    
    private function __clone() {}
    
    /**
     * @return void
     */
    public static function clear()
    {
        self::$events = array();
    }
    
    /**
     * @return array
     */
    public static function getEvents()
    {
        return self::$events;
    }
    
    /**
     * @param string $event
     * @param function|array $callback
     * @return bool
     */
    public static function has($event, $callback = null)
    {
        if (isset(self::$events[$event])) {

            if (is_null($callback)) {
                return true;
            } else {
                foreach (self::$events[$event] as $value) {
                    if($callback === $value) {
                        return true;
                    }                            
                }
                return false;
            }   
        } else {
            return false;
        }    

    }
    
    /**
     * @param string $event
     * @param function|array $callback
     * @return void
     */
    public static function unregister($event, $callback = null)
    {
        if (isset(self::$events[$event])) {

            if ($callback === null) {
                unset(self::$events[$event]);
                return true;
            }
            else {

                foreach (self::$events[$event] as $key => $value) {

                    if($callback === $value) {
                        unset(self::$events[$event][$key]);
                        return true;
                    }                            
                
                }        
            }

        } else {
            return false;
        }
    }

    /**
     * @param string $event
     * @param function $callback
     * @throws \Exception
     * @return void
     */
    public static function register($event, $callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception('Function not callable');
        }
        self::$events[$event][] = $callback;
    }

    /**
     * @param string $event
     * @param array $params
     * @throws \Exception
     * @return void
     */
    public static function trigger($event, array $params = array())
    {
        if (!self::has($event)) {
            throw new \Exception('Event is not registered');
        }

        foreach(self::$events[$event] as $callback) {
            call_user_func_array($callback, $params);
        }
    }

    /**
     * @param string $config_file - config file neve (events_admin.php)
     * @param string $index - megadható egy tömb (ahol az $index a tömb kulcsa), amibe belekerül a betöltött config tömb
     * @return void
     */
    public static function init($config_file, $index = null)
    {
        Config::load($config_file, $index);
       
        $events = (is_null($index)) ? Config::get($config_file) : Config::get($index . '.' . $config_file);
        
        foreach ($events as $event => $callback) {
            self::register($event, $callback);
        }
    }

}
?>