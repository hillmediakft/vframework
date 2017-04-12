<?php 
namespace System\Libs;

/**
* Response objektum
*/
class Response {
	/**
	 * @var  array  An array of status codes and messages
	 *
	 * See http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
	 * for the complete and approved list, and links to the RFC's that define them
	 */
	public $statuses = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a Teapot',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
	);

	/**
	 * @var  int  A HTTP status code
	 */
	public $status = 200;

	/**
	 * @var  array  HTTP fejléceket tartalmazza
	 */
	private $headers = array();

	/**
	 * @var  string  A válasz tartalma
	 */
	private $body = null;

	private $_base_url = BASE_URL;
	//private $_site_url;
	private $_current_url;



	public function __construct($body = null, $status = 200, array $headers = array())
	{
		foreach ($headers as $k => $v) {
			$this->setHeader($k, $v);
		}

		$this->body = $body;
		$this->status = $status;
		$this->_current_url = $this->_getCurrentUrl();
	}

	/**
	 * A jelenlegi url-t adja vissza
	 */
	private function _getCurrentUrl()
	{
		$request_uri = urldecode($_SERVER['REQUEST_URI']);
		return $this->_base_url . str_replace(BASE_PATH, '', trim($request_uri, '/'));
	}


/*
		public function setSiteUrl($site_url)
		{
			$this->_site_url = $site_url;
		}

		public function setCurrentUrl($current_url)
		{
			$this->_current_url = $current_url;
		}
*/


	/**
	 * Sets the response status code
	 *
	 * @param   int  $status  The status code
	 *
	 * @return  Response
	 */
	public function setStatus($status = 200)
	{
		$this->status = $status;
	}

	/**
	 * Adds a header to the queue
	 *
	 * @param   string       $name     The header name
	 * @param   string       $value    The header value
	 * @param   string|bool  $replace  Whether to replace existing value for the header, will never overwrite/be overwritten when false
	 *
	 * @return  Response
	 */
	public function setHeader($name, $value, $replace = true)
	{
		if ($replace) {
			$this->headers[$name] = $value;
		} else {
			$this->headers[] = array($name, $value);
		}
	}

	/**
	 * Content-type fejléc megadása
	 *
	 * @param   string       $value    The Content-Type header value
	 *
	 * @return  Response
	 */
	public function setContentType($value)
	{
		$this->setHeader('Content-Type', $value, true);
	}

	/**
	 * Adds multiple headers to the queue
	 *
	 * @param   array        $headers  Assoc array with header name / value combinations
	 * @param   string|bool  $replace  Whether to replace existing value for the header, will never overwrite/be overwritten when false
	 *
	 * @return  Response
	 */
	public function setHeaders($headers, $replace = true)
	{
		foreach ($headers as $key => $value)
		{
			$this->setHeader($key, $value, $replace);
		}
	}

	/**
	 * Gets header information from the queue
	 *
	 * @param   string  $name  The header name, or null for all headers
	 *
	 * @return  mixed
	 */
	public function getHeader($name = null)
	{
		if (!is_null($name))
		{
			return isset($this->headers[$name]) ? $this->headers[$name] : null;
		}
		else
		{
			return $this->headers;
		}
	}

	/**
	 * Fejlécek törlése
	 */
	public function clearHeaders()
	{
		$this->headers = array();
	}


	/**
	 * Sets the body for the response
	 *
	 * @param   string  $value  The response content
	 *
	 * @return  Response|string
	 */
	public function setBody($value)
	{
		$this->body = (string)$value;
	}

	/**
	 * Visszaadja a beállított body-t
	 *
	 * @return  string
	 */
	public function getBody()
	{
		return $this->body;
	}

    /**
     * Törli a body-t
     */
    public function clearBody() {
        $this->body = null;
    }


	/**
	 * Sends the headers if they haven't already been sent.  Returns whether
	 * they were sent or not.
	 *
	 * @return  bool
	 */
	public function sendHeaders()
	{
		if (!headers_sent())
		{
			// Send the protocol/status line first, FCGI servers need different status header
			if (!empty($_SERVER['FCGI_SERVER_VERSION']))
			{
				header('Status: ' . $this->status . ' ' . $this->statuses[$this->status]);
			}
			else
			{
				$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
				header($protocol . ' ' . $this->status . ' ' . $this->statuses[$this->status]);
			}

			foreach ($this->headers as $name => $value)
			{
				// Parse non-replace headers
				if (is_int($name) && is_array($value))
				{
					if (isset($value[0])) {
					 	$name = $value[0];
					}
					if (isset($value[1])) {
						$value = $value[1];
					}	
					//isset($value[0]) and $name = $value[0];
					//isset($value[1]) and $value = $value[1];
				}

				// Create the header
				if (is_string($name)) {
					$value = "{$name}: {$value}";
				}

				// Send it
				header($value, true);
			}
			return true;
		}
		return false;
	}

	/**
	 * Válasz elküldése a böngészőnek
	 *
	 * @param   string  $body  küldendő adat
	 * @return  void
	 */
	public function send($body = null)
	{
		// ha van beállított header, akkor elküldi a böngészőnek
		if (!empty($this->headers)) {
			$this->sendHeaders();
		}

		if (!is_null($body)) {
			$this->setBody($body);
		}

		echo $this->getBody();
	}

	/**
	 * JSON válasz
	 * @param array $array
	 * @param bool $flag 	ha false, akkor nem lesz exit parancs
	 */
	public function json(array $array, $flag = true)
	{
		$json = json_encode($array);
		$this->setHeader('Content-Type', 'application/json', true);
		$this->sendHeaders();
		echo $json;
		if ($flag) {
			exit;
		}
	}

	/**
	 * Redirects to another uri/url.  Sets the redirect header,
	 * sends the headers and exits.  Can redirect via a Location header
	 * or using a refresh header.
	 *
	 * The refresh header works better on certain servers like IIS.
	 *
	 * @param   string  $url     The url
	 * @param   string  $method  The redirect method
	 * @param   int     $code    The redirect status code
	 *
	 * @return  void
	 */
	public function redirect($url = '', $method = 'location', $code = 302)
	{
		$this->setStatus($code);

		if ( strpos($url, '://') === false ) {
			$url = ($url !== '') ? $this->_base_url . $url : $this->_base_url;
		}

		if ($method == 'location') {
			$this->setHeader('Location', $url);
		}
		elseif ($method == 'refresh') {
			$this->setHeader('Refresh', '0;url=' . $url);
		}
		else {
			return;
		}

		$this->send(true);
		exit;
	}

	/**
	 * Visszairányít az előző címre ha létezik	
	 *
	 * @param   string  $url     The url
	 * @param   string  $method  The redirect method
	 * @param   int     $code    The redirect status code
	 *
	 * @return  void
	 *
	 * @throws  \RuntimeException  If it would redirect back to itself
	 */
	public function redirectBack($url = '', $method = 'location', $code = 302)
	{
		// ha van referrer
		if (isset($_SERVER['HTTP_REFERER'])) {
			$referrer = $_SERVER['HTTP_REFERER'];
			// ha benne van a BASE URL a $referer-ben és a $referer != a current url-el
			if (strpos($referrer, $this->_base_url === 0) && $referrer != $this->_current_url) {
				// redirect back to where we came from
				$this->redirect($referrer, $method, $code);
			}
		}

		// ha az átirányítási cím megegyezik a jelenlegi címmel
		if ($url == $this->_current_url) {
			throw new \RuntimeException('You can not redirect back here, it would result in a redirect loop!');
		}

		$url = ($url === '') ? $this->_base_url : $url;
		// ha nincs referer, vagy egy külső link van megadva, akkor végrehajtódik egy normál átirányítás
		$this->redirect($url, $method, $code);
	}






    /* if the method send() was not explicitly called, the response is sent before object is destroyed */
    /*
    public function __destruct()
    {
       if (!$this->sent) {
           $this->sent = true;
           $this->send(true);
       }
    }
    */

}
?>