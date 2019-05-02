<?php
    require_once('lib/01_definitions.php');
    require_once('lib/02_inventory.php');
    require_once('lib/02_foods.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_new'])) {
        if (isset($_POST['new_food_id']) && $_POST['new_food_id'] != 'default_label') {
            $user_id = $_SESSION['current_user_id'];
            $food_id = $_POST['new_food_id'];
            $amount  = $_POST['new_amount'];
            if ($_POST['new_measure_type'] == "default_label") {
                $unit = NULL;
            } else {
                $unit = $_POST['new_measure_type'];
            }

            inv_add_food($user_id, $food_id, $amount, $unit);
        } else {
            add_page_message('red', 'You must select an item to add.');
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        if (isset($_POST['food_id'])) {
            $_SESSION['inv_update_food_id'] = $_POST['food_id'];
            header('location: inventory_update.php');
            exit(0);
        } else {
            add_page_message('red', 'You must select an item to update.');
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        if (isset($_POST['food_id'])) {
            $user_id                        = $_SESSION['current_user_id'];
            $_SESSION['inv_update_food_id'] = $_POST['food_id'];
            $query_result                   = inv_item_delete($user_id, $_POST['food_id']);
            echo 'Item deleted.';
        } else {
            add_page_message('red', 'You must select an item to delete.');
        }
    }

    display_page_messages(__FILE__);

    if (isset($_SESSION['current_user_id'])) {
        $user_name = $_SESSION['current_user_name'];
        $user_id   = $_SESSION['current_user_id'];

        $inventory = inv_get_all_foods($user_id);
        $types     = get_measure_types();
        ?>
        <form action="" method="post">
            <table>
                <tr>
                    <th>FOOD:</th>
                    <td>
                        <select name="new_food_id">
                            <option value="default_label" selected>-- Select --</option>
                            <?php
                                $new_foods = food_get_items_not_in_inv($user_id);
                                foreach ($new_foods as $row) {
                                    echo '<option value="' . $row['food_id'] . '">' . $row['food_name'] . '</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>AMOUNT:</th>
                    <td>
                        <input type="number" name="new_amount" min="0" step="any" class="num" value=""/>
                    </td>
                </tr>
                <tr>
                    <th>UNIT:</th>
                    <td>
                        <select name="new_measure_type">
                            <option value="default_label" selected>-- Select --</option>
                            <option value="">(None)</option>
                            <?php
                                $new_foods = food_get_items_not_in_inv($user_id);
                                foreach ($types as &$value) {
                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <input type="submit" value="Add New Item" name="add_new"/>
                    </th>
                </tr>
            </table>
            <br>
            <table>
                <tr>
                    <th>Food Name</th>
                    <th>Amount</th>
                    <th>Measure Type</th>
                    <th class="radio"></th>
                </tr>
                <?php
                    foreach ($inventory as $row) {
                        echo '<tr>';
                        echo '    <td>' . $row['food_name'] . '</td>';
                        echo '    <td class="right">' . $row['food_measure'] . '</td>';
                        echo '    <td>' . $row['food_measure_type'] . '</td>';
                        echo '    <td class="radio"><input type="radio" name="food_id" value="' .
                             $row['food_id'] .
                             '"/></td >';
                        echo '</tr>';
                    }
                ?>
            </table>
            <br>
            <input type="submit" value="Update Selected" name="update"/>
            <input type="submit" value="Delete Selected" name="delete"/>
        </form>
        <?php
    } else {
        display_no_login();
    }
    display_html_footer(__FILE__);
?>
