<?php
class Root extends Resource {

    public $map = array('welcome' => 'Resource_Welcome');

    public function greet($from) {
        $view = new View('root', array('from' => $from, 'welcomeURL' => $this->url('/welcome')));
        return $view->render();
    }

    public function GET() {
        return $this->greet('GET');
    }

    public function POST() {
        return $this->greet('POST');
    }
}
?>