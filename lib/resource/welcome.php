<?php
class Resource_Welcome extends Resource {
    
    public function GET() {
        return 'Welcome. I\'m Just a dummy. Go to <a href="'.$this->url('/').'">Root</a>';
    }

}
?>