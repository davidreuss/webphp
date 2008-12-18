<?php
class WebPHP {

    /**
     * WebPHP's autoloader
     *
     * It makes including classes automatic if you follow
     * the naming convention
     *
     * Ex. My_Awesome_Class -> lib/my/awesome/class.php
     *
     * @param string $classname
     */
    public static function autoload($classname) {
        $filename = str_replace('_', '/', strtolower($classname)).'.php';
        include $filename;
    }

    /**
     * Dispatch
     *
     * Run the app
     *
     * @param Resource $root
     * @return string the output of the request
     */
    public function dispatch() {
        $root = new Root();
        return $root->sendResponse();
    }
}
?>