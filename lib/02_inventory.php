<?php
    require_once('01_connector.php');

    function inv_item_delete($in_user_id, $in_food_id) {
        $query = <<<QUERY
            DELETE FROM st_inventory
                WHERE user_id_fk   = :user_id AND 
                      food_id_fk = :food_id;
QUERY;
        $data  = ['user_id'   => $in_user_id,
                  'food_id' => $in_food_id];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        if ($stmt->execute($data)) {
            return $stmt->rowCount();
        } else {
            return -1;
        }
    }
    function inv_get_all_foods($in_user_id) {
        $query = <<<QUERY
            SELECT food_id, food_name, food_measure, food_measure_type 
            FROM st_inventory, st_foods 
            WHERE   food_id_fk = food_id AND 
                    user_id_fk = :user_id 
            ORDER BY food_name;
QUERY;
        $data  = ['user_id' => $in_user_id,];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        $stmt->execute($data);
        $result = $stmt->fetchAll();
        return $result;
    }

    function inv_get_food($in_user_id, $in_food_id) {
        $query = <<<QUERY
            SELECT food_name, food_measure, food_measure_type 
            FROM st_inventory i, st_foods f 
            WHERE   i.food_id_fk = f.food_id AND 
                    i.user_id_fk = :user_id AND 
                    i.food_id_fk = :food_id 
            ORDER BY food_id
            LIMIT 1;
QUERY;
        $data  = ['user_id' => $in_user_id,
                  'food_id' => $in_food_id];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        $stmt->execute($data);
        $result = $stmt->fetch();
        return $result;
    }

    function inv_add_food($in_user_id, $in_food_id, $in_measure, $in_measure_type) {
        $query = <<<QUERY
            INSERT INTO st_inventory    (user_id_fk, food_id_fk, food_measure, food_measure_type) 
                                VALUES  (:user_id, :food_id, :measure, :measure_type);
QUERY;
        $data  = ['measure'      => $in_measure,
                  'measure_type' => $in_measure_type,
                  'user_id'      => $in_user_id,
                  'food_id'      => $in_food_id];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        if ($stmt->execute($data)) {
            $result = $stmt->fetchAll();
            return $result;
        } else {
            return -1;
        }
    }

    function inv_modify_food($in_user_id, $in_food_id, $in_measure, $in_measure_type) {
        $query = <<<QUERY
            UPDATE st_inventory SET
                food_measure=:measure,
                food_measure_type=:measure_type
            WHERE   user_id_fk=:user_id AND 
                    food_id_fk=:food_id;
QUERY;
        $data  = ['measure'      => $in_measure,
                  'measure_type' => $in_measure_type,
                  'user_id'      => $in_user_id,
                  'food_id'      => $in_food_id];

        $pdo = get_pdo_connector();
        //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare($query);

        if ($stmt->execute($data)) {
            return $stmt->rowCount();
        } else {
            return -1;
        }
    }