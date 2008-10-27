<?php
class WebPHP {

    public $baseURL;
    public $base;

    public function __construct() {
        $this->base = dirname($_SERVER['SCRIPT_NAME']);
        $this->baseURL = $this->getBaseURL($_SERVER['REQUEST_URI']);
    }

    public static function autoload($classname) {
        $filename = str_replace('_', '/', strtolower($classname)).'.php';
        require_once($filename);
    }

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
     * I'm not very happy about the implementation of the following
     * but it seems to work nicely so this acts as a note to look at this
     * once in the future (yeah right)
     */
    public function findDelegate($uri, $root) {
        $handler = $root;
        $parts = array();
        $uriParts = explode('/', $uri);
        foreach ($uriParts as $key => $part) {
            if (!$part) continue;
            $subspace = array_slice($uriParts, $key, count($uriParts));
            $handler->setSubspace(join('/', $subspace));
            if (isset($handler->map[$part])) {
                $parts[] = $part;
                $handler = new $handler->map[$part](
                    join('/', array_merge(array($this->base), $parts)), $subspace);
            } else {
                return $handler->forward($part);
            }
        }
        return $handler;
    }

    public function dispatch(Resource $root/*, View $decorator*/) {
        $delegate = $this->findDelegate($this->baseURL, $root);
        $view = $delegate->sendResponse();
        if ($view) {
            // Decorate with default page view
            //$decorator->set('content', $view->fetch());
            //$decorator->render();
            $view->render();
        } else {
            // No view received - holy fuck, abort!
        }
    }
}
?>