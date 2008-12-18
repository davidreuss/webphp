<?php
class Resource {

    /**
     * The map for the resource
     *
     * Is used for mapping URL's to certain resources
     *
     * E.g
     * $map = array('hello' => 'Resource_Hello')
     *
     * .. maps 'http://<app root>/hello' to the Resource_Hello class.
     */
    public $map = array();

    /**
     * The current url state of the Resource
     *
     * @param array $urlState
     */
    private $urlState = array();

    /**
     * The request uri for the app with whatever "prefix"
     * there might be
     */
    private static $requestURI = '';

    /**
     * Resource URL split on '/'
     */
    private $baseURLParts = array();

    /**
     * Constructor
     */
    public function __construct(Resource $parent = null) {
        if ($parent) {
            // Pass on from parent
            $this->baseURLParts = $parent->getBaseURLParts();
        } else {
            $this->initializeRequestURIAndBaseURL();
        }
    }

    /**
     * Send the response
     *
     * Inspect request first, and determine if we
     * should forward
     *
     * @return string response
     */
    public function sendResponse() {
        $this->handle();
        $next = $this->next();
        if ($next) {
            if (isset($this->map[$next])) {
                $resource = new $this->map[$next]($this);
                return $resource->sendResponse();
            }
            return $this->forward($next);
        }
        return $this->execute();
    }

    /**
     * Get the response from the current resource
     *
     * @todo Could be built to handle alternative request types
     *       from looking at Http-Method-Equivalent
     *
     * @access private
     * @return string the response
     */
    private function execute() {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                return $this->POST();
            default:
                return $this->GET();
            break;
        }
    }

    /**
     * Find next if set
     *
     * @return string next url part
     */
    private function next() {
        return array_shift($this->baseURLParts);
    }

    /**
     * Build URL
     *
     * It will look at the current resource url state and apply
     * them as well.
     *
     * The args given to the method takes precendence, so it's easy
     * to override arguments set in the url state
     *
     * @param string $href
     * @param array $args, arguments to the url
     * @return string the URL
     */
    public function url($href = "", $args = array()) {
        $protocol = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
        $server = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $serverport = $_SERVER['SERVER_PORT'];
        $base = (isset($href[0]) && $href[0] == '/') ? dirname($_SERVER['SCRIPT_NAME']) : self::$requestURI . '/';
        $port = (isset($serverport) && $serverport != 80) ? ':' . $serverport : '';
        $url = $protocol . '://' . $server . $port . $base;
        $args = array_merge($this->getURLState(), $args);
        if (!empty($args)) {
            $href .= '?' . http_build_query($args);
        }
        return $url . $href;
    }

    /**
     * Get name of resource
     *
     * @return string the name
     */
    public function getName() {
        if (isset($this->baseURLParts[0])) {
            return $this->baseURLParts[0];
        }
    }

    /**
     * Forward request to controller if any
     *
     * @param string $name
     * @access protected
     */
    protected function forward($name) {
        $handler = $this;
        if ($name) {
            if (isset($this->map[$name])) {
                $handler = new $this->map[$name]($this);
            }
        }
        return $handler->execute();
    }

    /**
     * Get the current URL state
     *
     * @return array
     */
    public function getURLState() {
        return $this->urlState;
    }

    /**
     * Set the URL state for the resource
     */
    public function setURLState($state) {
        $this->urlState = $state;
    }

    /**
     * Inspect request uri and setup accordingly
     *
     * @access private
     * @return void
     */
    private function initializeRequestURIAndBaseURL() {
        $base = dirname($_SERVER['SCRIPT_NAME']);
        $requestURI = $_SERVER['REQUEST_URI'];
        $tmp = explode('?', $requestURI);
        if (count($tmp) > 0) {
            list ($requestURI, $queryString) = $tmp;
        }
        $baseURL = ltrim(str_replace($base, '', $requestURI), '/');
        $this->baseURLParts = explode('/', $baseURL);
        self::$requestURI = $base . '/' . $baseURL;
    }

    /**
     * Get's the base URL parts
     */
    public function getBaseURLParts() {
        return $this->baseURLParts;
    }

    /**
     * GET stub
     *
     * Should be overridden by subclasses
     *
     * @return string response from request
     */
    public function GET() {}

    /**
     * POST stub
     *
     * Should be overridden by subclasses
     *
     * @return string response from request
     */
    public function POST() {}

    /**
     * Handle stub
     *
     * Should be overridden by subclasses
     *
     * Custom "hook", you should implement in your resources
     * for setting up $map, or whatever state needed before
     * determining if we should forward, or how to handle the
     * request
     *
     * @return void
     */
    public function handle() {}
}
?>