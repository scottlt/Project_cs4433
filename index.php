<?php
    require_once('lib/01_definitions.php');
    session_start();
    display_html_header(__FILE__);
    display_topnav(__FILE__);
    display_page_messages(__FILE__);

    if (isset($_SESSION['current_user_id'])) {
        echo 'WELCOME, ' . $_SESSION['current_user_name'] . '!';
    } else {
        display_no_login();
    }
    display_html_footer(__FILE__);
?>

