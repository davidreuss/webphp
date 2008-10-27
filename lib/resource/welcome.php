<?php
class Resource_Welcome extends Resource {
    
    public function GET() {
        return new View('index', array('content' => 'Welcome to my site.'));
    }
    
}
?>