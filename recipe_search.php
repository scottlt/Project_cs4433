<?php
    require_once('lib/01_definitions.php');
    require_once('lib/02_recipes.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    display_page_messages(__FILE__);

    if (isset($_SESSION['current_user_id'])) {
        $user_name   = $_SESSION['current_user_name'];
        $user_id     = $_SESSION['current_user_id'];
        $search_text = NULL;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
            if (isset($_POST['search_text'])) {
                $search_text = $_POST['search_text'];
            }
        }
        $search_results = recipes_get_search($user_id, $search_text);
        ?>
        <form action="" method="post">
            <?php echo '<input type="text" name="search_text" value="' . $search_text . '"/>'; ?>
            <input type="submit" value="Search" name="search"/>
        </form>
        <br>
        <table>
            <tr>
                <th>Recipe Name</th>
                <th>User Rating</th>
            </tr>
            <?php
                foreach ($search_results as $row) {
                    echo '<tr>';
                    echo '    <td><a href="recipe_view.php?recipe_id=' .
                         $row['recipe_id'] .
                         '">' .
                         $row['recipe_name'] .
                         '</a></td> ';
                    if (isset($row['rating_value'])) {
                        echo '    <td class="center"> ' . $row['rating_value'] . ' out of 5 </td> ';
                    } else {
                        echo '    <td class="center">N/A</td> ';
                    }
                    echo '</tr > ';
                }
            ?>
        </table>
        <?php
    } else {
        display_no_login();
    }
    display_html_footer(__FILE__);
?>
