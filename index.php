<?php
/**
 * index.php
 *
 * Mini Blog App
 *
 * @author     Michael Love
 * @license    https://opensource.org/licenses/MIT
 * @version    1.0.0
 * @link       http://mini-blog-challenge.thesandboxserver.com/
 * @see        https://github.com/moongear-mike/mini-blog
 * @since      File available since Release 1.0.0
 *
 */

// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//  Force https
if ($_SERVER["HTTPS"] != "on") {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

session_start();

// Database definitions
define('DB_NAME', 'teamgrey_mini-blog');
define('DB_USER', 'teamgrey_mini-blog');
define('DB_PASSWORD', 'B,gk8-js,zFK');
define('DB_HOST', 'localhost');
define('DATE_FORMAT', 'l F j, Y @ g:ia');

// Form default empty values and rules
$form_values = array(
    "blog_name" => array("value" => "", "required" => true, "format" => "alphanum", "friendly" => "Your Name"),
    "blog_title" => array("value" => "", "required" => true, "format" => "alphanum", "friendly" => "Blog Title"),
    "blog_content" => array("value" => "", "required" => true, "format" => "alphanum", "friendly" => "Blog Content"),
    "blog_email" => array("value" => "", "required" => false, "format" => "email", "friendly" => "Your Email"),
    "blog_submit" => array("value" => "", "required" => false, "format" => "none", "friendly" => "The Submit Button")
);

//Set how many posts to display
$blog_posts_per_page = 5;

//Set some defaults
$_SESSION['errors'] = empty($_SESSION['errors']) ? array() : $_SESSION['errors'];
$_SESSION['notices'] = empty($_SESSION['notices']) ? array() : $_SESSION['notices'];
$_SESSION['save_for_later'] = array();


//Sanitize $_POST
if (!empty($_POST)) {
    $clean_object = sanitize_this_blog_object($_POST, $form_values);
} else {
    $clean_object = $form_values;
}

// Make some decisions
switch (true) {
    case !empty($clean_object['errors']):
        // Handle errors
        $_SESSION['save_for_later'] = $clean_object;

        do_reload();
        break;
    case !empty($clean_object['blog_submit']['value']):
        // insert blog post
        do_create($clean_object);
        do_reload();
        break;
    default:
        // Do everything else
}

// Check to see if we have session vars.
// If so then let's use them
if (!empty($_SESSION['save_for_later'])) {
    // Copy the saved date to $clean_object
    $clean_object = $_SESSION['save_for_later'];
    // Clear this as it is no longer needed.
    $_SESSION['save_for_later'] = array();
}


// Paint the Header
do_header();

// Paint the Posts
do_get_posts($blog_posts_per_page);

// Paint the Form
do_form($clean_object);

// Paint the Footer
do_footer();


//  The functions that do stuff
//  The functions that do stuff
//  The functions that do stuff
//  The functions that do stuff
//  The functions that do stuff

// Connect to the database
function db_connect()
{
    // Create connection
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

//Create the blog post
function do_create($clean_object)
{

    $mysqli = db_connect();

    $q_make_post = "INSERT INTO `posts` 
                                        (`posts`.`title`, 
                                         `posts`.`content`, 
                                         `posts`.`creator_name`, 
                                         `posts`.`creator_email`) 
                            VALUES      ('{$clean_object['blog_title']['value']}', 
                                         '{$clean_object['blog_content']['value']}', 
                                         '{$clean_object['blog_name']['value']}',
                                         ''); ";

    $r_make_post = $mysqli->query($q_make_post) or die("Failed is insert new post: " . $mysqli->error);

}

// Reload the page
function do_reload()
{
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /");
    die('Something did not work');
}

//This is a rudimentary sanitize function.
function sanitize_this_blog_object($form_object, $form_values)
{

    $clean_object = array();

    // Set a place to record any errors
    $clean_object['errors'] = array();
    $clean_object['notices'] = array();


    if (empty($_POST)) {
        return array();
    } else {

//        $form_object = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        foreach ($form_object as $key => $value) {

            if (key_exists($key, $form_values)) {  // If part of the $form_values then continue otherwise toss it.

                // Sanitize or error
                switch (true) {
                    case $form_values[$key]['format'] === "alphanum":
                        $value = preg_replace("/[^A-Za-z0-9 ]/", '', $value);
                        break;
                    case $form_values[$key]['format'] === "email" && !filter_var($value, FILTER_VALIDATE_EMAIL):
                        $clean_object['errors'][$key] = $form_values[$key]['friendly'] . " is not a valid email.";
                        break;
                    case $form_values[$key]['format'] === "numeric" && !is_numeric($value):
                        $clean_object['errors'][$key] = $form_values[$key]['friendly'] . " is not a valid number.";
                        break;
                    default:
                        // and so on and so forth with other types of validation and sanitation.
                }
                // Check if is required and if empty record error
                if ($form_values[$key]['required'] === true && empty($value)) {
                    $clean_object['errors'][$key] = $form_values[$key]['friendly'] . " is required";
                }

                $clean_object[$key]["value"] = trim($value);

            }
        }
    }
    return $clean_object;
}

//  The functions that paint stuff
//  The functions that paint stuff
//  The functions that paint stuff
//  The functions that paint stuff
//  The functions that paint stuff

// Debugger output
function output_pre($some_object_or_strong)
{
    echo "<pre>";
    print_r($some_object_or_strong);
    echo "</pre>";
}

function do_header()
{
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Description" content="Hey!  This is a blog page that works!">
        <title>A Blog Page</title>
        <style>
            html {
                padding: 0;
                margin: 0;
            }

            body {
                font-family: "Fira Sans", "Source Sans Pro", Helvetica, Arial, sans-serif;
                padding: 0;
                margin: 0;
                background-color: #000;
            }

            #page-container {
                background-color: #fff;
                padding: 20px;
                border: 5px solid orange;
                margin: 0 auto;
                max-width: 1200px;
            }

            #page-container header h1 {
                font-size: 5em;
            }

            #contant-wrapper {
            }

            #contant-wrapper p {
            }

            [id^="post_container_"] {
                padding: 0;
                border: 1px solid #00000026;
                margin: 0 0 30px 0;
            }

            [id^="post_container_"]:nth-of-type(odd) {
                background-color: #00000008;
            }

            h2.post-title {
                margin: 0;
                width: 100%;
                padding: 5px 10px 5px 20px;
                font-size: 34px;
                background-color: orange;
                box-sizing: border-box;
            }

            div.credit-wrapper {
                padding: 5px 10px 5px 20px;
                width: 100%;
                border-bottom: 1px solid #000;
                box-sizing: border-box;
                background-color: #000;
                color: #fff;
            }

            div.credit-wrapper div {
                display: inline-block;
            }

            div.credit-wrapper div.author-name {
                font-weight: 600;
            }

            div.credit-wrapper div.post-date {
            }

            div.post-content {
                padding: 40px 10px 50px 20px;
            }
            #new_post_wrapper {
                border: 5px solid orange;
                background-color: #0000000f;
            }
            #new_post_wrapper h2 {
                margin: 0;
                padding: 20px 10px 5px 20px;
            }
            #form_wrapper {
                20px 10px 80px 20px;
            }

            #form_wrapper form {
                padding: 0 30px 60px 30px;
            }

            #form_wrapper form label:not([for="blog_submit"]) {
                display: block;
                text-transform: uppercase;
                font-size: 12px;
                font-weight: 600;
                padding: 20px 0 2px 10px;
            }

            #form_wrapper form input#blog_name, #form_wrapper form input#blog_title, #form_wrapper form textarea#blog_content  {
                min-width: 80%;
                font-size: 18px;
                padding: 10px;
                border-radius: 10px;
                box-shadow: none;
            }

            textarea#blog_content {
                min-height: 300px;
            }

            [for="blog_submit"] {
                font-size: 0;
                line-height: 0;
            }

            #blog_submit {
                min-width: 40%;
                padding: 10px;
                border-radius: 10px;
                box-shadow: none;
                background-color: #55bd46;
                color: #fff;
                font-size: 24px;
                font-weight: 600;
            }

            #footer {
                width: 100%;
                text-align: center;
                padding: 20px;
            }

            #footer div {
                display: inline-block;
            }

            #footer div.copyright {
            }

            #footer div.address {
            }

            #footer div.stop-float {
            }
        </style>
    </head>
    <body>
    <!--Start Page Container-->
    <div id="page-container">
    <!--Start Header-->
    <header>
        <h1>Mini Blog</h1>
    </header>
    <!--Start Content Wrapper-->
    <div id="contant-wrapper">
    <p>Some information about this blog page....</p>

    <?php
}

// Get newest posts up to $blog_posts_per_page
function do_get_posts($blog_posts_per_page)
{
    // Prevent connecting again
    global $mysqli;
    if (!empty($mysqli)) {
        return $mysqli;
    }

    $mysqli = db_connect();


    $q_get_blog = "SELECT `posts`.`post_id`, 
                           `posts`.`title`, 
                           `posts`.`content`, 
                           `posts`.`creator_name`, 
                           `posts`.`creator_email`, 
                           `posts`.`date_posted`, 
                           `posts`.`date_updated`, 
                           `posts`.`status` 
                    FROM   `posts` 
                    WHERE  `status` = 1 
                    ORDER  BY `post_id` DESC 
                    LIMIT  {$blog_posts_per_page} ";

    $r_get_blog = $mysqli->query($q_get_blog) or die("Failed: " . $mysqli->error);

    while ($post = $r_get_blog->fetch_object()) {
        do_post($post);
    }
}

//Insert the individual post
function do_post($post)
{

    // $post has:
    // $post->post_id,
    // $post->title,
    // $post->content,
    // $post->creator_name,
    // $post->creator_email,
    // $post->date_posted,
    // $post->date_updated,
    // $post->status

    ?>
    <div id="post_container_<?php echo $post->post_id; ?>">
        <h2 class="post-title"><?php echo $post->title; ?></h2>
        <div class="credit-wrapper">by
            <div class="author-name"><?php echo $post->creator_name; ?></div>
            on
            <div class="post-date"><?php echo date(DATE_FORMAT, strtotime($post->date_posted)); ?></div>
        </div>
        <div class="post-content"><?php echo nl2br($post->content); ?></div>
    </div>

    <?php
}

function do_form($clean_object)
{
    //blog post (Title, Content, Name of Creator, Date/Time),

    ?>
    <div id="new_post_wrapper">
        <h2>Create a new blog post</h2>
        <div id="form_wrapper">
            <form action="/" name="blog_form" method="post">

                <label for="blog_name">Your Name</label>
                <input id="blog_name" name="blog_name" type="text" placeholder="Please type your name"
                       value="<?php echo $clean_object['blog_name']["value"]; ?>" required>

                <label for="blog_title">Blog Title</label>
                <input id="blog_title" name="blog_title" type="text" placeholder="Give your post a title"
                       value="<?php echo $clean_object['blog_title']["value"]; ?>" required>

                <label for="blog_content">Content</label>
                <textarea id="blog_content" name="blog_content"
                          required><?php echo $clean_object['blog_content']["value"]; ?></textarea>

                <label for="blog_submit">Blog Submit</label>
                <input id="blog_submit" name="blog_submit" type="submit" value="Submit Blog Post">

            </form>
        </div>
    </div>
    <?php
}

function do_footer()
{
    ?>
    </div>
    <!--End Content Wrapper-->

    <!--Start Footer-->
    <div id="footer">
        <div class="copyright">
            Ⓒ 2019 Bark Bark
        </div> -
        <div class="address">
            Dogs are awesome.
        </div>
        <div class="stop-float"></div>
    </div>
    <!--End Footer-->
    </div>
    <!--End Page Container-->
    </body>
    </html>
    <?php
}