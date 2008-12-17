<?php
/**
 * $_GLOBALS anyone?
 */
class Registry {

    public $data;

    /**
     * Get an instance of the Registry
     *
     * @static
     * @return Registry
     */
    public static function instance() {
        static $instance = null;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Set value in the registry
     */
    public function set($key, $val) {
        $this->data[$key] = $val;
    }

    /**
     * Get value from the registry
     */
    public function get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
    }

}
?>