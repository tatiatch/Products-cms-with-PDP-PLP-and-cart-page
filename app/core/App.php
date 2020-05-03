<?php

use app\core\Error;

spl_autoload_register('autoLoader');

function autoLoader($className) {
    $extension = ".php";
    $file = str_replace('\\',DIRECTORY_SEPARATOR,$className);
    if (file_exists($file.$extension)) require_once $file.$extension;
    require_once 'libs/rb.php';
}

class App {
    protected $controller;
    protected $method;
    protected $params;

    public function __construct()
    {
        session_start();
        if($_GET['url']) {
            $url = $this->parseUrl($_GET['url']);
            if ($url[0] === 'admin') {
                array_shift($url);
                $this->handleAdminUrl($url);
            } else {
                $this->handlePublicUrl($url);
            }
        }
    }

    public function handleAdminUrl($url) {
        $this->controller = 'app\controllers\admin\\'.ucfirst($url[0]);
        if ($url[0] === 'login') {
            array_shift($url);
            new $this->controller($_SERVER['REQUEST_METHOD'], $this->params);
            return null;
        }
        array_shift($url);
        if (class_exists($this->controller)) {
            $this->params = $url;
            if ($this->confirmAdminLogIn()) {
                new $this->controller($_SERVER['REQUEST_METHOD'], $this->params);
            } else {
                self::redirect_to(sprintf('/%s/admin/login', $GLOBALS['project_root_folder']));
            }
        } else {
            $this->render404();
        }
    }

    private function handlePublicUrl($url) {

    }

    private function render404() {
        new Error('404','Sorry, page not found');
    }

    private function confirmAdminLogIn() {
        return isset($_SESSION['admin_id']);
    }

    private function parseUrl($url) {
        return explode('/', filter_var(rtrim($_GET['url'], '/')));
    }

    public static function redirect_to($location){
        header("Location: " . $location);
        exit;
    }
}