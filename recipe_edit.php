<?php
    require_once('lib/01_definitions.php');
    require_once('lib/02_recipes.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    display_page_messages(__FILE__);

    // Form submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $recipe_id               = $_POST['recipe_id'];
            $new_recipe_name         = $_POST['recipe_name'];
            $new_recipe_instructions = $_POST['recipe_instructions'];
            $new_recipe_tags         = $_POST['recipe_tags'];
            $result                  =
                recipe_modify($recipe_id, $new_recipe_name, $new_recipe_instructions, $new_recipe_tags);
            // Set the message to be sent to the inventory page
            if ($result == 1) {
                add_page_message('green', 'Database was updated successfully.');
            } elseif ($result == -1) {
                add_page_message('red', 'An error occurred when updating the database.');
            } elseif ($result == 0) {
                add_page_message('red', 'No rows were updated.');
            } else {
                add_page_message('red', 'Multiple rows were updated.');
            }
        }
        //header('location: inventory.php');
        //exit(0);
    }
    display_page_messages(__FILE__);

    // If the user is set and we have a food ID to update
    if (isset($_SESSION['current_user_id']) && isset($_SESSION['recipe_update_id'])) {
        $user_id   = $_SESSION['current_user_id'];
        $recipe_id = $_SESSION['recipe_update_id'];
        unset($_SESSION['inv_update_food_id']);

        $query_result = recipe_get_by_id($user_id, $recipe_id);
        ?>
        <form action="" method="POST">
            <table class="min_left">
                <?php
                    echo '<tr><td class="center">Recipe&nbsp;Name</td>';
                    echo '<td><input type="textbox" name="recipe_name" class="max" maxlength="60" value="' .
                         $query_result['recipe_name'] .
                         '"/></td></tr>';

                    echo '<tr><td class="center">Recipe&nbsp;Tags</td>';
                    echo '<td><input type="textbox" name="recipe_tags" class="max" maxlength="255" value="' .
                         $query_result['recipe_tags'] .
                         '"/></td></tr>';

                    echo '<tr><td class="center">Recipe&nbsp;Instructions</td>';
                    echo '<td><textarea name="recipe_instructions" maxlength="4096">';
                    echo $query_result['recipe_instructions'];
                    echo '</textarea></td></tr>';
                ?>
            </table>
            <br>
            <?php echo '<input type="hidden" name="recipe_id" value="' . $recipe_id . '"/>'; ?>
            <input type="submit" value="Save" name="update"/>
            <input type="submit" value="Cancel" name="cancel"/>
        </form>
        <?php

    } elseif (isset($_SESSION['inv_update_food_id']) == FALSE) {
        echo 'ERROR: NO FOOD ID.';
    } else {
        display_no_login();
    }
    display_html_footer(__FILE__);
?>

