<?php


namespace app\controllers\admin;

use app\core\DB;
use App;
use app\core\Error;
use R;

class Login
{
    protected $pageTitle = 'Login';
    protected $admin;
    protected $username;
    protected $password;

    public function __construct($method, $params = null)
    {
        require_once 'resources/layouts/header.phtml';
        switch ($method) {
            case 'GET': $this->renderlLoginPage(); break;
            case 'POST': $this->attemptLogin(); break;
            default: $this->renderlLoginPage();
        }
        require_once 'resources/layouts/footer.phtml';
    }

    public function renderlLoginPage() {
        require_once 'app/views/admin/login/login.phtml';
    }

    private function passwordCheck($password, $existing_hash){
        return md5($password) === $existing_hash;
    }

    private function attemptLogin(){
        DB::connect();
        $this->admin = R::find('admin', 'username = ?', [$_POST['username']]);
        if ($this->admin) {
            if ($this->passwordCheck($_POST['password'], $this->admin[1]['password'])) {
                $_SESSION['admin_id'] = $this->admin[1]['id'];
                App::redirect_to(sprintf('/%s/admin/products', $GLOBALS['project_root_folder']));
            } else {
                new Error('404','Sorry, username or password is incorrect');
            }
        } else {
            new Error('404','Sorry, admin not found');
        }
    }
}