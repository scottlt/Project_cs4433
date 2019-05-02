<?php

    // Defined as constants so that they can't be changed
    DEFINE('DB_USER', 'st_php_rw');
    DEFINE('DB_PASS', '44c83eb5bb587a7fa018103824b22164');
    DEFINE('DB_HOST', 'localhost');
    DEFINE('DB_NAME', 'st_project');

    function get_pdo_connector() {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . '', DB_USER, DB_PASS);
        return $pdo;
    }



