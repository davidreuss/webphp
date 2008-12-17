<?php
class WebPHP {

    public $baseURL;
    public $base;

    /**
     * Constructor
     *
     * Initializes base and baseURL, from $_SERVER
     */
    public function __construct() {
        $this->base = dirname($_SERVER['SCRIPT_NAME']);
        $this->baseURL = $this->getBaseURL($_SERVER['REQUEST_URI']);
    }

    /**
     * WebPHP's autoloader
     *
     * It makes including classes automatic if you follow
     * the naming convention
     *
     * Ex. My_Awesome_Class -> lib/my/awesome/class.php
     *
     * @param $classname
     */
    public static function autoload($classname) {
        $filename = str_replace('_', '/', strtolower($classname)).'.php';
        include $filename;
    }

    /**
     * Get baseURL from a given request URI
     *
     * @param string $requestURI
     * @return string
     */
    private function getBaseURL($requestURI) {
        $tmp = explode('?', $requestURI);
        if (count($tmp) == 2) {
            list ($requestURI, $queryString) = $tmp;
        }
        $baseURL = str_replace($this->base, '', $requestURI);
        $baseURL = ltrim($baseURL, '/');
        return $baseURL;
    }

    /**
     * Dispatch
     *
     * Run the app
     *
     * @param Resoruce $root
     * @return string the output of the request
     */
    public function dispatch() {
        $root = new Root();
        $root->setBaseURL($this->base, $this->baseURL);
        return $root->sendResponse();
    }
}
?>