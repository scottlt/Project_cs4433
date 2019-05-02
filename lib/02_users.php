<?php
    require_once('01_connector.php');

    function user_get_all() {
        $query = <<<QUERY
SELECT user_id, user_name 
    FROM st_users 
ORDER BY user_id;
QUERY;

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    function user_get_by_id($in_user_id) {
        $query = <<<QUERY
SELECT user_name 
    FROM st_users 
WHERE user_id = :user_id
LIMIT 1;
QUERY;
        $data  = ['user_id' => $in_user_id];

        $pdo  = get_pdo_connector();
        $stmt = $pdo->prepare($query);
        $stmt->execute($data);
        $result = $stmt->fetch();
        return $result;
    }
