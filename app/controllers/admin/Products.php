<?php

namespace app\controllers\admin;

use app\core\ControllerCRUD;
use app\core\DB;
use App;
use R;
use app\core\Error;

class Products extends ControllerCRUD
{
    protected $products;
    protected $product;
    protected $pageTitle;

    public function __construct($method, $params = null)
    {
        DB::connect();
        $this->pageTitle = 'Products';
        parent::__construct($method, $params);
    }

    public function showAll()
    {
        $this->products = R::findAll('products');
        require_once 'app/views/admin/products/productsAll.phtml';
    }

    public function showById($id)
    {
        $this->product = R::load('products', $id);
        if ($this->product['id']) {
            $this->pageTitle = sprintf('Product edit %s', $this->product['name']);
            require_once 'app/views/admin/products/productsCreate.phtml';
        } else {
            new Error('404','Sorry, product with given id not found');
        }
    }

    public function create()
    {
        $this->pageTitle = 'Products create';
        require_once 'app/views/admin/products/productsCreate.phtml';
    }

    public function save()
    {
        $this->product = R::dispense('products');
        $this->product->name = $_POST['name'];
        $this->product->description = $_POST['description'];
        $this->product->price = $_POST['price'];
        $image = $_POST['image'] ? $this->uploadImages() : $this->setDefaultImage();
        if ($image) {
            $this->product->image = $image;
            R::store($this->product);
            App::redirect_to('products');
        }
    }

    public function update($id)
    {
        $this->product = R::load('products', $id);
        $this->product->name = $_POST['name'];
        $this->product->description = $_POST['description'];
        $this->product->price = $_POST['price'];
        $image = $_POST['image'] ? $this->uploadImages() : $this->setDefaultImage();
        if ($image) {
            $this->product->image = $image;
            R::store($this->product);
            App::redirect_to('../products');
        }
    }

    public function delete($id)
    {
        $this->product = R::load('products', $id);
        R::trash($this->product);
        App::redirect_to('../../products');
    }

    private function uploadImages() {
        $target_dir = sprintf('%s/%s/public/media/products/', $_SERVER['DOCUMENT_ROOT'], $GLOBALS['project_root_folder']);
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if (!$check) {
                new Error('404','File is not an image.');
                $uploadOk = 0;
            }
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            new Error('404','Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
            $uploadOk = 0;
        }

        $target_file = $target_dir . basename($_FILES["image"]["tmp_name"]) . '.' . $imageFileType;

        // Check if file already exists
        if (file_exists($target_file)) {
            new Error('404','Sorry, file already exists.');
            $uploadOk = 0;
        }

        if ($uploadOk == 0 || !move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            new Error('404','Sorry, there was an error uploading your file.');
            return false;
        }

        return '/'.trim($target_file, $_SERVER['DOCUMENT_ROOT']);
    }

    private function setDefaultImage() {
        return sprintf('/%s/public/media/products/default.png', $GLOBALS['project_root_folder'] );
    }
}