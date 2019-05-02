<?php
    require_once('01_connector.php');

    function food_get_items_not_in_inv($in_user_id) {
        $pdo = get_pdo_connector();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = <<<QUERY
            SELECT food_id, food_name
                FROM st_foods f
                    LEFT JOIN (SELECT food_id_fk
                                    FROM st_inventory
                                    WHERE user_id_fk=:user_id) i
                        ON i.food_id_fk = f.food_id
            WHERE i.food_id_fk IS NULL
            ORDER BY food_name;
QUERY;
        $stmt  = $pdo->prepare($query);
        $data  = ['user_id' => $in_user_id];
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        return $results;
    }

