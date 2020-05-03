<?php
$GLOBALS['project_root_folder'] = trim(dirname(__FILE__), $_SERVER['DOCUMENT_ROOT']);
require_once 'app/core/App.php';
new App();
