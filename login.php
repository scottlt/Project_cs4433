<?php
    require_once('lib/01_definitions.php');
    require_once('lib/02_users.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    display_page_messages(__FILE__);

    if (isset($_SESSION['current_user_id'])) {
        session_unset();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        echo '<br>';
        if (isset($_POST['user_id'])) {
            $user_id                       = $_POST['user_id'];
            $query_result                  = user_get_by_id($user_id);
            $user_name                     = $query_result['user_name'];
            $_SESSION['current_user_id']   = $user_id;
            $_SESSION['current_user_name'] = $user_name;
            header("location: index.php");
        } else {
            echo "You must select a user.<br><br>";
        }
    }
?>
Please login a user:<br>
<?php

    $all_users = user_get_all();
    if ($all_users) {
        ?>
        <form action="" method="post">
            <table class="no_border">
                <?php
                    foreach ($all_users as $row) {
                        echo '<tr>';
                        echo '<td class="radio"><input type="radio" name="user_id" value="' .
                             $row['user_id'] .
                             '"/></td >';
                        echo '<td>' . $row['user_name'] . '</td>';
                        echo '</tr >';
                    }
                ?>
            </table>
            <br>
            <input type="submit" value="Submit" name="submit"/>
        </form>
        <?php
    } else {
        echo 'No data retrieved from st_users';
    }
    display_html_footer(__FILE__);
?>
