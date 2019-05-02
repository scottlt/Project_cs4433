<?php
    require_once('lib/01_definitions.php');
    require_once('lib/02_inventory.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    display_page_messages(__FILE__);

    // Form submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $user_id = $_SESSION['current_user_id'];
            $amount  = $_POST['amount'];
            // Ensure that a unit of measure was selected
            if ($_POST['measure_type'] == "default_label") {
                $unit = NULL;
            } else {
                $unit = $_POST['measure_type'];
            }
            $food_id = $_POST['food_id'];
            // Update the food item in the inventory
            $result = inv_modify_food($user_id, $food_id, $amount, $unit);
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
        header('location: inventory.php');
        exit(0);
    }
    display_page_messages(__FILE__);

    // If the user is set and we have a food ID to update
    if (isset($_SESSION['current_user_id']) && isset($_SESSION['inv_update_food_id'])) {
        $user_id = $_SESSION['current_user_id'];
        $food_id = $_SESSION['inv_update_food_id'];
        unset($_SESSION['inv_update_food_id']);

        $query_result = inv_get_food($user_id, $food_id);

        $types = get_measure_types();

        ?>
        <form action="" method="post">
            <table>
                <tr>
                    <th>FOOD:</th>
                    <td>
                        <?php
                            echo '<input type="hidden" name="food_id" value="' . $food_id . '"/>';
                            echo $query_result['food_name'] . '  ';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>AMOUNT:</th>
                    <td>
                        <?php
                            echo '<input type="number" name="amount" min="0" step="any" class="num" value="' .
                                 $query_result['food_measure'] .
                                 '"/> ';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>UNIT:</th>
                    <td>
                        <select name="measure_type">
                            <option value="default_label" selected>-- Select --</option>
                            <option value="">(None)</option>
                            <?php
                                foreach ($types as &$value) {
                                    if ($value == $query_result['food_measure_type']) {
                                        echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                    } else {
                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <input type="submit" value="Save" name="update"/>
                        <input type="submit" value="Cancel" name="cancel"/>
                    </th>
                </tr>
            </table>

        </form>
        <?php

    } elseif (isset($_SESSION['inv_update_food_id']) == FALSE) {
        echo 'ERROR: NO FOOD ID.';
    } else {
        display_no_login();
    }
    display_html_footer(__FILE__);
?>
