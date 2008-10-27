<?php
class Root extends Resource {
    
    public $map = array('welcome' => 'Resource_Welcome');

    public function GET() {
        return new View('root', array('welcomeURL' => $this->url('/welcome/')));
    }
    
    public function POST() {
        return new View('post', array('data' => $this->POST['data']));
    }
}
?>