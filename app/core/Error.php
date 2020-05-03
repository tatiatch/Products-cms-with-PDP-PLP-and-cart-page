<?php

namespace app\core;

class Error
{
    protected $message;

    public function __construct($error_type, $message)
    {
        $this->message = $message;
        switch ($error_type) {
            case '404': $this->handle404(); break;
        }
    }

    private function handle404() {
        require_once 'app/views/admin/errorPages/error404.phtml';
    }
}