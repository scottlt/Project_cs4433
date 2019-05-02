<?php
    require_once('01_connector.php');

    function recipe_get_ingredients($in_recipe_id) {
        //Get Recipe Ingredients
        $query = <<<QUERY

SELECT
    food_measure,
    food_measure_type,
    food_name,
    food_description
FROM st_ingredients ingr
         INNER JOIN st_foods food
                    ON food.food_id = ingr.food_id_fk
WHERE ingr.recipe_id_fk = :recipe_id
ORDER BY food_name;
QUERY;
        $data  = ['recipe_id' => $in_recipe_id];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        $stmt->execute($data);
        $result = $stmt->fetchAll();
        return $result;
    }

    function recipe_get_by_id($in_user_id, $in_recipe_id) {
        //Get Recipe and Rating
        $query = <<<QUERY
SELECT
    recipe_id,
    recipe_name,
    recipe_instructions,
    recipe_tags,
    rating_value
FROM st_recipes reci
         LEFT JOIN (SELECT recipe_id_fk, rating_value FROM st_ratings WHERE user_id_fk = :user_id) rate
                   ON rate.recipe_id_fk = reci.recipe_id
WHERE reci.recipe_id = :recipe_id;
QUERY;
        $data  = ['user_id' => $in_user_id, 'recipe_id' => $in_recipe_id];

        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        $stmt->execute($data);
        $result = $stmt->fetch();
        return $result;
    }

    /***
     * @param $in_user_id
     * @param $in_search_text
     *
     * @return array
     */
    function recipes_get_search($in_user_id, $in_search_text) {
        $query = NULL;

        if (isset($in_search_text) && !empty($in_search_text)) {
            // SEARCH TEXT EXISTS
            $query = <<<QUERY
SELECT DISTINCT
    recipe_id,
    recipe_name,
    rating_value
FROM (
         SELECT
             recipe_id,
             recipe_name,
             recipe_tags,
             rating_value,
             (MATCH(recipe_name, recipe_tags) AGAINST(:search_text IN BOOLEAN MODE) +
              MATCH(food_name) AGAINST(:search_text IN BOOLEAN MODE)) AS search_score
         FROM st_recipes reci
                  LEFT JOIN (SELECT
                                 recipe_id_fk,
                                 food_id_fk
                             FROM st_ingredients) ingr
                            ON ingr.recipe_id_fk = reci.recipe_id
                  LEFT JOIN (SELECT
                                 food_id,
                                 food_name
                             FROM st_foods) food
                            ON food.food_id = ingr.food_id_fk
                  LEFT JOIN (SELECT recipe_id_fk, rating_value FROM st_ratings WHERE user_id_fk = :user_id) rate
                            ON rate.recipe_id_fk = reci.recipe_id
         WHERE MATCH(recipe_name, recipe_tags) AGAINST(:search_text IN BOOLEAN MODE)
            OR MATCH(food_name) AGAINST(:search_text IN BOOLEAN MODE)
         ORDER BY search_score DESC, recipe_name ASC ) main;
QUERY;
            $data  = ['user_id' => $in_user_id, 'search_text' => $in_search_text];
        } else {
            // SEARCH TEXT DOES NOT EXIST
            $query = <<<QUERY
SELECT DISTINCT
    recipe_id,
    recipe_name,
    rating_value
FROM (
         SELECT
             recipe_id,
             recipe_name,
             recipe_tags,
             rating_value
         FROM st_recipes reci
                  LEFT JOIN (SELECT
                                 recipe_id_fk,
                                 food_id_fk
                             FROM st_ingredients) ingr
                            ON ingr.recipe_id_fk = reci.recipe_id
                  LEFT JOIN (SELECT
                                 food_id,
                                 food_name
                             FROM st_foods) food
                            ON food.food_id = ingr.food_id_fk
                  LEFT JOIN (SELECT recipe_id_fk, rating_value FROM st_ratings WHERE user_id_fk = :user_id) rate
                            ON rate.recipe_id_fk = reci.recipe_id
         ORDER BY recipe_name ASC) main;
QUERY;
            $data  = ['user_id' => $in_user_id];
        }

        // GET ON WITH IT
        $pdo = get_pdo_connector();

        $stmt = $pdo->prepare($query);

        $stmt->execute($data);
        $result = $stmt->fetchAll();
        return $result;
    }

    function recipe_modify($in_recipe_id, $in_recipe_name, $in_recipe_instructions, $in_recipe_tags) {
        $query = <<<QUERY
            UPDATE st_recipes SET
                recipe_name             = :recipe_name,
                recipe_instructions     = :recipe_instructions,
                recipe_tags             = :recipe_tags
            WHERE   recipe_id = :recipe_id;
QUERY;
        $data  = ['recipe_id'           => $in_recipe_id,
                  'recipe_name'         => $in_recipe_name,
                  'recipe_instructions' => $in_recipe_instructions,
                  'recipe_tags'         => $in_recipe_tags];

        $pdo = get_pdo_connector();
        //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare($query);

        if ($stmt->execute($data)) {
            return $stmt->rowCount();
        } else {
            return -1;
        }
    }