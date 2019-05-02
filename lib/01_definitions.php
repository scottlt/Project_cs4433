<?php

    function get_measure_types() {
        $arr_types = array('cup',
                           'fluid ounce',
                           'gallon',
                           'ounce',
                           'pint',
                           'pound',
                           'quart',
                           'tablespoon',
                           'teaspoon');
        return $arr_types;
    }

    function display_html_header($in_caller) {
        $caller = basename($in_caller);
        $title  = NULL;
        switch ($caller) {
            case 'index.php':
                $title = 'Home';
                break;
            case 'inventory.php':
                $title = 'Inventory';
                break;
            case 'inventory_update.php':
                $title = 'Update Inventory Item';
                break;
            case 'recipe_search.php':
                $title = 'Recipe Search';
                break;
            case 'recipe_view.php':
                $title = 'View Recipe';
                break;
            default:
                $title = ucwords($caller);
        }
        echo '<html>';
        echo '<head><title>' . $title . '</title></head>';
        echo '<link href="css/00_main_style.css?v=11" rel="stylesheet" type="text/css">';
        echo '<body>';
    }

    function display_topnav($in_caller) {
        $caller = basename($in_caller);
        echo '<div class="topnav"> ';
        if (isset($_SESSION['current_user_id']) && $caller !== 'logout.php') {
            echo '<a class="logged_in">Logged in as: ' . $_SESSION['current_user_name'] . '</a>';
            echo '<a href = "logout.php">Logout</a>';

            if ($caller == 'index.php') {
                echo '<a class="active">Home</a>';
            } else {
                echo '<a href = "index.php">Home</a>';
            }
            if ($caller == 'inventory.php') {
                echo '<a class="active">Inventory</a>';
            } else {
                echo '<a href = "inventory.php">Inventory</a>';
            }
            /*
            if ($caller == 'ratings.php') {
                echo '<a class="active">My Ratings </a>';
            } else {
                echo '<a href = "ratings.php">My Ratings </a>';
            }
            */
            if ($caller == 'recipe_search.php') {
                echo '<a class="active">Recipes</a>';
            } else {
                echo '<a href = "recipe_search.php">Recipes</a>';
            }
            if ($caller == 'recipe_view.php') {
                echo '<a class="active">View Recipe</a>';
            }
        } else {
            if ($caller == 'index.php') {
                echo '<a class="active">Home</a>';
            } else {
                echo '<a href = "index.php">Home</a>';
            }
            if ($caller == 'login.php') {
                echo '<a class="active">Login</a>';
            } else {
                echo '<a href = "login.php">Login</a>';
            }
        }
        echo '</div>';
        echo '<br>';
    }

    function display_page_messages($in_caller) {
        $caller = basename($in_caller);

        if (isset($_SESSION['red_msg'])) {
            echo '<div class="red_msg">';
            foreach ($_SESSION['red_msg'] as $msg) {
                echo $msg . '<br>';
            }

            echo '</div><br>';
            unset($_SESSION['red_msg']);
        }
        if (isset($_SESSION['green_msg'])) {
            echo '<div class="green_msg">';
            foreach ($_SESSION['green_msg'] as $msg) {
                echo $msg . '<br>';
            }
            echo '</div><br>';
            unset($_SESSION['green_msg']);
        }
    }

    function add_page_message($type, $msg) {
        if ($type == 'green') {
            $_SESSION['green_msg'][] = $msg;
        }
        if ($type == 'red') {
            $_SESSION['red_msg'][] = $msg;
        }
    }

    function display_no_login() {
        echo 'You are not logged in.';
        echo '<br><br>';
        echo 'Click <a href="login.php">here</a> to go to the login page.';
    }

    function display_html_footer($in_caller) {
        $caller = basename($in_caller);
        echo '</body>';
        echo '</html>';
    }

