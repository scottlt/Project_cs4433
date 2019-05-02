<?php
    require_once('lib/01_definitions.php');
    require_once('lib/02_recipes.php');
    require_once('lib/02_ratings.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    display_page_messages(__FILE__);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_rating'])) {
        $user_id        = $_SESSION['current_user_id'];
        $rate_recipe_id = $_POST['recipe_id'];
        $rating_new     = $_POST['rating_new'];
        $rating_old     = $_POST['rating_old'];
        if (isset($user_id) && isset($rate_recipe_id)) {
            $query_result = NULL;
            if ($rating_old != NULL && $rating_new == -1) {
                // Delete the rating
                $query_result = rating_delete($user_id, $rate_recipe_id);
            } elseif ($rating_old == NULL && $rating_new != -1) {
                // Insert the new rating
                $query_result = rating_insert($user_id, $rate_recipe_id, $rating_new);
            } elseif ($rating_old != NULL && $rating_new != $rating_old) {
                // Update with new rating
                $query_result = rating_update($user_id, $rate_recipe_id, $rating_new);
            }
            var_dump($query_result);
        } else {
            echo 'ERROR - USER_ID = ' . $user_id . ', RECIPE_ID = ' . $rate_recipe_id;
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_recipe'])) {
        $_SESSION['recipe_update_id'] = $_POST['recipe_id'];
        header('location: recipe_edit.php');
        exit(0);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_ingredients'])) {
        $_SESSION['recipe_update_id'] = $_POST['recipe_id'];
        header('location: recipe_ingredients_edit.php');
        exit(0);
    }

    if (isset($_SESSION['current_user_id'])) {
    $user_id   = $_SESSION['current_user_id'];
    $recipe_id = NULL;
    if (isset($_GET['recipe_id'])) {
    $recipe_id   = $_GET['recipe_id'];
    $recipe_data = recipe_get_by_id($user_id, $recipe_id);

    if ($recipe_data) {
    $user_rating         = $recipe_data['rating_value'];
    $recipe_name         = $recipe_data['recipe_name'];
    $recipe_tags         = $recipe_data['recipe_tags'];
    $recipe_instructions = $recipe_data['recipe_instructions'];
    $ingredient_data     = recipe_get_ingredients($recipe_id);

?>
<form action="" method="POST">

    <input type="submit" value="Edit Recipe" name="edit_recipe"/>
    <?php
        echo '<h1>' . $recipe_name . '</h1>';
        echo '<input type="hidden" name="rating_old" value="' . $user_rating . '"/>';
        echo 'User Rating: <input type="hidden" name="recipe_id" value="' . $recipe_id . '"/>';
    ?>
    <select class="rating" name="rating_new">

        <?php
            if (isset($user_rating) || $user_rating == 0) {
                echo '<option value="-1">(None)</option>';
            } else {
                echo '<option selected value="-1">(None)</option>';
            }
            for ($i = 1; $i <= 5; $i++) {
                if ($i == $user_rating) {
                    echo '<option selected value="' . $i . '">' . $i . '</option>';
                } else {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
            }
        ?>
    </select>
    <input type="submit" value="Save" name="save_rating"/>
    <?php

        echo '</form>';
        echo 'Tags: ' . ($recipe_tags ? $recipe_tags : 'N/A');
        echo '<h2>Ingredients</h2>';
        echo '<table class="no_border">';

        foreach ($ingredient_data as $row) {
            echo '<tr>';
            echo '<td class="right">' . $row['food_measure'] . '</td>';
            echo '<td class="center">' . $row['food_measure_type'] . '</td>';
            echo '<td>' . $row['food_name'] . '</td>';
            echo '<td>' . $row['food_description'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<br>';
        echo '<input type="submit" value="Edit Ingredients" name="edit_ingredients"/>';
        echo '<h2>Instructions</h2>' . nl2br($recipe_instructions);
        echo '<br>';
        } else {
        echo '<br>';
        echo 'No data for Recipe ID: ' . $recipe_id;
    }
        } else {
        echo '<br>';
        echo 'Recipe ID required.';
    }
        } else {
        display_no_login();
    }
        display_html_footer(__FILE__);
    ?>
