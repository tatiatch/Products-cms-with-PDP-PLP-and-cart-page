<?php

namespace app\core;

abstract class ControllerCRUD
{
    protected $pageTitle;
    /**
     * ControllerCRUD constructor.
     * @param $method
     * @param null $params
     */
    public function __construct($method, $params = null)
    {
        require_once 'resources/layouts/header.phtml';
        if ($method === 'GET') {
            if (empty($params)) $this->showAll();
            else if ($params[0] === 'create') $this->create();
            else if (ctype_digit($params[0]) && count($params) == 1) $this->showById($params[0]);
            else echo '404 page';
        } else if ($method === 'POST') {
            if (empty($params)) $this->save();
            else if (ctype_digit($params[0]) && count($params) == 1) $this->update($params[0]);
            else if (ctype_digit($params[0]) && count($params) == 2 && $params[1] === 'delete') $this->delete($params[0]);
            else echo '404 page';
        }
        require_once 'resources/layouts/footer.phtml';
    }

    /**
     * @return mixed
     */
    abstract public function showAll();

    /**
     * @param $id
     * @return mixed
     */
    abstract public function showById($id);

    /**
     * @return mixed
     */
    abstract public function create();

    /**
     * @return mixed
     */
    abstract public function save();

    /**
     * @param $id
     * @return mixed
     */
    abstract public function update($id);

    /**
     * @param $id
     * @return mixed
     */
    abstract public function delete($id);
}