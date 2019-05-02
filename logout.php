<?php
    require_once('lib/01_definitions.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    display_page_messages(__FILE__);

    if (isset($_SESSION['current_user_id'])) {
        session_unset();
        echo 'You have been logged out.';
        echo '<br><br>';
    }

    echo 'Click <a href="login.php">here</a> to go to the login page.';

    display_html_footer(__FILE__);
?>
