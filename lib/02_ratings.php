<?php
    require_once('01_connector.php');

    function rating_insert($in_user_id, $in_recipe_id, $in_rating) {
        $query = <<<QUERY
            INSERT INTO st_ratings 
                (user_id_fk, recipe_id_fk, rating_value) 
                VALUES
                (:user_id, :recipe_id, :rating);
QUERY;
        $data  = ['user_id'   => $in_user_id,
                  'recipe_id' => $in_recipe_id,
                  'rating'    => $in_rating];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        if ($stmt->execute($data)) {
            $result = $stmt->fetchAll();
            return $result;
        } else {
            return -1;
        }
    }

    function rating_update($in_user_id, $in_recipe_id, $in_rating) {
        $query = <<<QUERY
            UPDATE st_ratings
                SET rating_value = :rating
                WHERE user_id_fk   = :user_id AND 
                      recipe_id_fk = :recipe_id;
QUERY;
        $data  = ['user_id'   => $in_user_id,
                  'recipe_id' => $in_recipe_id,
                  'rating'    => $in_rating];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        if ($stmt->execute($data)) {
            return $stmt->rowCount();
        } else {
            return -1;
        }
    }

    function rating_delete($in_user_id, $in_recipe_id) {
        $query = <<<QUERY
            DELETE FROM st_ratings
                WHERE user_id_fk   = :user_id AND 
                      recipe_id_fk = :recipe_id;
QUERY;
        $data  = ['user_id'   => $in_user_id,
                  'recipe_id' => $in_recipe_id];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        if ($stmt->execute($data)) {
            return $stmt->rowCount();
        } else {
            return -1;
        }
    }
