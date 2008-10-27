<?php
class Resource {

    public $POST;
    public $GET;

    public $base;
    public $subspace;
    
    public $map = array();
    
    public $urlState = array();
    
    public function __construct($base = '') {
        $this->base = $base;
    }
    
    public function sendResponse() {
        $this->handleRequest();
        // FIXME: This could be built to handle PUT/DELETE
        // from looking at Http-Method-Equivalent for alternative request types
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                $this->POST = $_POST;
                return $this->POST();
            default:
                $this->GET = $_GET;
                return $this->GET();
            break;
        }
    }

    public function url($href = "", $args = array()) {
        $protocol = 'http'; // FIXME: Does not handle https!
        $server = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $serverport = $_SERVER['SERVER_PORT'];
        $base = (isset($href[0]) && $href[0] == '/') ? dirname($_SERVER['SCRIPT_NAME']) : $this->base . '/';
        $port = (isset($serverport) && $serverport != 80) ? ':' . $serverport : '';
        $url = $protocol . '://' . $server . $port . $base;
        $args = array_merge($this->getURLState(), $args);
        if (!empty($args)) {
            $href .= '?' . http_build_query($args);
        }
        return $url . $href;
    }
    
    public function forward($name) {
        // throw new Exception('No forward defined');
    }
    
    public function getSubspace() {
        return $this->subspace;
    }
    
    public function setSubspace($subspace) {
        $this->subspace = $subspace;
    }
    
    public function getURLState() {
        return $this->urlState;
    }
    
    public function setURLState($state) {
        $this->urlState = $state;
    }

    public function handleRequest() {}
    public function GET() {}
    public function POST() {}
}
?>