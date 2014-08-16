<?php
namespace Framework\Core;


class Response extends Component
{

    /**
     * @var int HTTP status code
     */
    protected $status;

    /**
     * @var string HTTP response body
     */
    protected $body;

    /**
     * @var int Length of HTTP response body
     */
    protected $length;

    /**
     * @var array HTTP response codes and messages
     */
    protected static $messages = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => '418 I\'m a teapot',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );

    /**
     * Constructor
     * @param string $body   The HTTP response body
     * @param int $status The HTTP response status
     * @param array	$headers The HTTP response headers
     */
    public function __construct($body = '', $status = 200, $headers = array())
    {
        $this->setStatus($status);
        $this->write($body);
    }
	
    /**
     * Append HTTP response body
     * @param  string   $body       Content to append to the current HTTP response body
     * @param  bool     $replace    Overwrite existing response body?
     * @return string               The updated HTTP response body
     */
    public function write($body, $replace = false)
    {
        if ($replace) {
            $this->body = $body;
        } else {
            $this->body .= (string)$body;
        }
        $this->length = strlen($this->body);

        return $this->body;
    }

	/**
	 * Get response status
	 * @return integer
	 */
    public function getStatus()
    {
        return $this->status;
    }

	/**
	 * Set response status
	 * @return integer
	 */
    public function setStatus($status)
    {
        $this->status = (int)$status;
    }

	/**
	 * Get response body
	 * @return integer
	 */
    public function getBody()
    {
        return $this->body;
    }

	/**
	 * Set response status
	 * @return integer
	 */
    public function setBody($content)
    {
        $this->write($content, true);
    }

	/**
	 * Get response body length
	 * @return integer
	 */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Redirect
     * @param string $url The redirect destination
     * @param int $status The redirect HTTP status code
     */
    public function redirect( $url, $status = 302 )
    {
        $this->setStatus($status);
        header('Location: ' . $url);
    }

    /**
     * Empty response?
     * @return bool
     */
    public function isEmpty()
    {
        return in_array($this->status, array(201, 204, 304));
    }

    /**
     * OK?
     * @return bool
     */
    public function isOk()
    {
        return $this->status === 200;
    }

    /**
     * Successful?
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->status >= 200 && $this->status < 300;
    }

    /**
     * Redirect?
     * @return bool
     */
    public function isRedirect()
    {
        return in_array($this->status, array(301, 302, 303, 307));
    }

    /**
     * Forbidden?
     * @return bool
     */
    public function isForbidden()
    {
        return $this->status === 403;
    }

    /**
     * Not Found?
     * @return bool
     */
    public function isNotFound()
    {
        return $this->status === 404;
    }

    /**
     * Is client error?
     * @return bool
     */
    public function isClientError()
    {
        return $this->status >= 400 && $this->status < 500;
    }

    /**
     * Is server error?
     * @return bool
     */
    public function isServerError()
    {
        return $this->status >= 500 && $this->status < 600;
    }

    /**
     * Get message for HTTP status code
     * @param  int $status
     * @return string|null
     */
    public static function getMessageForCode($status)
    {
        if (isset(self::$messages[$status])) {
            return self::$messages[$status];
        } else {
            return null;
        }
    }
}