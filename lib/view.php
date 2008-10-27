<?php
class View {
    
    static $pathPrefix = '';
    static $pathSuffix = '';
    
    public $path;
    public $model;
    
    public function __construct($path = "", $model = array()) {
        $this->path = $path;
        $this->model = $model;
    }
    public function render() {
        if ($this->model) extract($this->model);
        include(self::$pathPrefix . $this->path . self::$pathSuffix);
    }
    public function fetch() {
        ob_start();
        $this->render();
        $rendered = ob_get_clean();
        return $rendered;
    }
    
    public function get($key) {
        if (isset($this->model[$key])) {
            return $this->model[$key];   
        }
    }
    
    public function set($key, $value) {
        $this->model[$key] = $value;
    }
}

?>