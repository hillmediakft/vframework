<?php
namespace System\Libs;

/**
 * Class Router
 * @package     Bramus\Router MÓDOSÍTVA!!!
 * @author      Bram(us) Van Damme <bramus@bram.us>
 * @copyright   Copyright (c), 2013 Bram(us) Van Damme
 * @license     MIT public license
 */
class Router
{
        /**
         * @var string Controller neve
         */
        public $controller;

        /**
         * @var string Action neve
         */
        public $action;

        /**
         * @var array Nevesített paraméterek
         */
        public $named_params = array();

        /**
         * @var array Paraméterek (számmal indexelt tömbben)
         */
        public $params;

        /**
         * @var array Helyörzők: reguláris kifejezések
         */
        public $shorthand = array(
            ':controller' => '([a-zA-Z_\-][a-zA-Z0-9_\-]+)',
            ':action' => '([a-zA-Z_\-][a-zA-Z0-9_\-]+)',
            ':any' => '([\w]+)', //alfanumerikus karakterek és aláhúzás
            ':id' => '(\d+)',
            ':num' => '([\d]+)',
            ':year' => '([12][0-9]{3})',
            ':month' => '(0[1-9]|1[012])',
            ':day' => '(0[1-9]|[12][0-9]|3[01])',
            ':title' => '([a-zA-Z_\-][a-zA-Z0-9_\-]+)',
            ':hash' => '(.+)',
            ':filename' => '([a-zA-Z0-9_\-\.]+)'
        );

        /**
         * @var string Current uri-t tárolja
         */
        public $current_uri;


    /**
     * @var array The route patterns and their handling functions
     */
    private $afterRoutes = array();

    /**
     * @var array The before middleware route patterns and their handling functions
     */
    private $beforeRoutes = array();

    /**
     * @var object|callable The function to be executed when no route has been matched
     */
    protected $notFoundCallback;

    /**
     * @var string Current base route, used for (sub)route mounting
     */
    private $baseRoute = '';

    /**
     * @var string The Request Method that needs to be handled
     */
    private $requestedMethod = '';

                    /**
                     * @var string The Server Base Path for Router Execution
                     */
                    //private $serverBasePath;


        /**
         * Reguláris kifejezés hozzáadása
         * @param string $key
         * @param string $exp
         */
        public function addShorthand($key, $exp)
        {
            if (strpos($key, ':') === false) {
                $key = ':' . $key;
            }

            $this->shorthand[$key] = $exp;
        }

        /**
         * Current uri beállítása
         */
        public function setCurrentUri($uri)
        {
            $this->current_uri = '/' . $uri;
        }

    /**
     * Store a before middleware route and a handling function to be executed when accessed using one of the specified methods
     *
     * @param string $methods Allowed methods, | delimited
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id
     */
    public function before($methods, $pattern, $fn, $param_names = array())
    {
        $pattern = $this->baseRoute . '/' . trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        foreach (explode('|', $methods) as $method) {
            $this->beforeRoutes[$method][] = array(
                'pattern' => $pattern,
                'fn' => $fn,
                'param_names' => $param_names                
            );
        }
    }

    /**
     * Store a route and a handling function to be executed when accessed using one of the specified methods
     *
     * @param string $methods Allowed methods, | delimited
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id
     */
    public function match($methods, $pattern, $fn, $param_names = array())
    {
        $pattern = $this->baseRoute . '/' . trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        foreach (explode('|', $methods) as $method) {
            $this->afterRoutes[$method][] = array(
                'pattern' => $pattern,
                'fn' => $fn,
                'param_names' => $param_names                
            );
        }
    }

    /**
     * Shorthand for a route accessed using any method
     *
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id     
     */
    public function all($pattern, $fn, $param_names = array())
    {
        $this->match('GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $pattern, $fn, $param_names);
    }

    /**
     * Shorthand for a route accessed using GET
     *
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id     
     */
    public function get($pattern, $fn, $param_names = array())
    {
        $this->match('GET', $pattern, $fn, $param_names);
    }

    /**
     * Shorthand for a route accessed using POST
     *
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id     
     */
    public function post($pattern, $fn, $param_names = array())
    {
        $this->match('POST', $pattern, $fn, $param_names);
    }

    /**
     * Shorthand for a route accessed using PATCH
     *
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id     
     */
    public function patch($pattern, $fn, $param_names = array())
    {
        $this->match('PATCH', $pattern, $fn, $param_names);
    }

    /**
     * Shorthand for a route accessed using DELETE
     *
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id     
     */
    public function delete($pattern, $fn, $param_names = array())
    {
        $this->match('DELETE', $pattern, $fn, $param_names);
    }

    /**
     * Shorthand for a route accessed using PUT
     *
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id     
     */
    public function put($pattern, $fn, $param_names = array())
    {
        $this->match('PUT', $pattern, $fn, $param_names);
    }

    /**
     * Shorthand for a route accessed using OPTIONS
     *
     * @param string $pattern A route pattern such as /about/system
     * @param object|callable $fn The handling function to be executed
     * @param array $param_names paraméterek pl.: id     
     */
    public function options($pattern, $fn, $param_names = array())
    {
        $this->match('OPTIONS', $pattern, $fn, $param_names);
    }

    /**
     * Mounts a collection of callbacks onto a base route
     *
     * @param string $baseRoute The route sub pattern to mount the callbacks on
     * @param callable $fn The callback method
     */
    public function mount($baseRoute, $fn)
    {
        // Track current base route
        $curBaseRoute = $this->baseRoute;

        // Build new base route string
        $this->baseRoute .= $baseRoute;

        // Call the callable
        call_user_func($fn);

        // Restore original base route
        $this->baseRoute = $curBaseRoute;
    }

        /**
         * Get all request headers
         *
         * @return array The request headers
         */
        /*
        public function getRequestHeaders()
        {
            // If getallheaders() is available, use that
            if (function_exists('getallheaders')) {
                return getallheaders();
            }

            // Method getallheaders() not available: manually extract 'm
            $headers = array();
            foreach ($_SERVER as $name => $value) {
                if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                    $headers[str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }

            return $headers;
        }
        */

    /**
     * Get the request method used, taking overrides into account
     *
     * @return string The Request method to handle
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];

/*
        // Take the method as found in $_SERVER
        $method = $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $headers = $this->getRequestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }

        return $method;
*/  
    }


    /**
     * @return array
     */
    public function runBefore()
    {
        // Define which method we need to handle
        $this->requestedMethod = $this->getRequestMethod();

        $callbacks = array();
        // Handle all before middlewares
        if (isset($this->beforeRoutes[$this->requestedMethod])) {
            $callbacks = $this->handle($this->beforeRoutes[$this->requestedMethod]);
        }

        return $callbacks;    
    }

    /**
     * 
     */
    public function runAfter($callback = null)
    {
        if ($callback) {
            $callback();
        }       
    }

    /**
     * Execute the router: Loop all defined before middleware's and routes, and execute the handling function if a match was found
     *
     * @return array
     */
    public function run()
    {
        // Define which method we need to handle
        $this->requestedMethod = $this->getRequestMethod();

        $callbacks = array();

        // Végigfut az útvonalakon
        if (isset($this->afterRoutes[$this->requestedMethod])) {
            $callbacks = $this->handle($this->afterRoutes[$this->requestedMethod], true);
        }

        // ha nincs találat
        if (empty($callbacks)) {

            if ($this->notFoundCallback && is_callable($this->notFoundCallback)) {
                $callbacks[] = array(
                    'closure' => $this->notFoundCallback,
                    'params' => array(),
                    );

            } elseif (is_string($this->notFoundCallback) && stripos($this->notFoundCallback, '@') !== false) {
                list($controller, $action) = explode('@', $this->notFoundCallback);
                $callbacks[] = array(
                    'controller' => $controller,
                    'action' => $action,
                    'params' => array(),
                    'named_params' => array()
                    );                
            }

        }

        return $callbacks;
    }

    /**
     * Set the 404 handling function
     *
     * @param object|callable $fn The function to be executed
     */
    public function set404($fn)
    {
        $this->notFoundCallback = $fn;
    }

    /**
     * Végigfut a kapott útvonalakon, és egyezés esetén berakja a $callbacks tömbbe az útvonalhoz tartozó elemeket 
     *
     * @param array $routes Collection of route patterns and their handling functions
     * @param boolean $quitAfterRun Does the handle function need to quit after one route was matched?
     * @return array
     */
    private function handle($routes, $quitAfterRun = false)
    {
        $callbacks = array();
        
        // Loop all routes
        foreach ($routes as $route) {

            // Kicseréli a jelöléseket (pl.: :action) reguláris kifejezésekké ($shorthand tömb alapján)
            $route['pattern'] = str_replace(array_keys($this->shorthand), array_values($this->shorthand), $route['pattern']);

            // egyezés keresése
            if (preg_match_all('#^' . $route['pattern'] . '$#', $this->current_uri, $matches, PREG_OFFSET_CAPTURE)) {
                // Rework matches to only contain the matches, not the orig string
                $matches = array_slice($matches, 1);

                // Extract the matched URL parameters (and only the parameters)
                $this->params = array_map(function ($match, $index) use ($matches) {

                    // We have a following parameter: take the substring from the current param position until the next one's position (thank you PREG_OFFSET_CAPTURE)
                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                    } // We have no following parameters: return the whole lot
                    else {
                        return (isset($match[0][0]) ? trim($match[0][0], '/') : null);
                    }
                }, $matches, array_keys($matches));

  
            // névvel ellátott paraméterek beállítása
            if(isset($this->params[0]) && $this->params[0] != null && !empty($route['param_names'])) {
                $this->named_params = array_combine($route['param_names'], $this->params);
            }

                // closure függvény esetén
                if (is_callable($route['fn'])) {
                    $callbacks[] = array('closure' => $route['fn'], 'params' => $this->params);
                }
                // controller & action megadásakor
                elseif (stripos($route['fn'], '@') !== false) {
                    // explode segments of given route
                    list($controller, $action) = explode('@', $route['fn']);
                    // controller es action tulajdonság beállítása                    
                    $this->controller = $controller;
                    $this->action = $action;

                    $callbacks[] = array('controller' => $this->controller, 'action' => $this->action, 'params' => $this->params, 'named_params' => $this->named_params);
                }

                // If we need to quit, then quit
                if ($quitAfterRun) {
                    break;
                }
            }
        }

        return $callbacks;
    }
     
}