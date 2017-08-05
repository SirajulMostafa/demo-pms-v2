<?php

/*
 * Setting session
 */
if (!session_id()) {
    session_start();
}
define('DEBUG', true);
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}

/*
 * All index name will be capitalized
 */
$config = array();
$success = '';
$error = '';
$warning = '';
$information = '';
$page_id = '';
if (isset($_GET['success']) AND $_GET['success'] != '') {
    $success = base64_decode(trim($_GET['success']));
}
if (isset($_GET['error']) AND $_GET['error'] != '') {
    $error = base64_decode(trim($_GET['error']));
}
if (isset($_GET['warning']) AND $_GET['warning'] != '') {
    $warning = base64_decode(trim($_GET['warning']));
}
if (isset($_GET['information']) AND $_GET['information'] != '') {
    $information = base64_decode(trim($_GET['information']));
}

$config['BASE_DIR'] = dirname(dirname(__FILE__));


// Change when upload to different domain //
/* Development Server */
$config['SITE_NAME'] = 'Pharmacy Management System';
$config['BASE_URL'] = 'http://localhost/pms_v2/';
$config['ROOT_DIR'] = '/pms_v2/';
$config['DB_TYPE'] = 'mysql';
$config['DB_HOST'] = 'localhost';
$config['DB_NAME'] = 'pmsv2';
$config['DB_USER'] = 'root';
$config['DB_PASSWORD'] = '';



date_default_timezone_set('Asia/Dhaka');
$config['MASTER_ADMIN_EMAIL'] = "sirajulmost@gmail.com";
$config['PASSWORD_KEY'] = "#PMS#";
$config['ADMIN_PASSWORD_LENGTH_MAX'] = 15;
$config['ADMIN_PASSWORD_LENGTH_MIN'] = 5;
$config['ADMIN_COOKIE_EXPIRE_DURATION'] = (60 * 60 * 24 * 30);

$config['IMAGE_UPLOAD_PATH'] = $config['BASE_DIR'] . '/public/upload';
$config['IMAGE_UPLOAD_URL'] = $config['BASE_URL'] . 'public/upload';
/*
 * Set the database connection here
 */
$con = mysqli_connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PASSWORD'], $config['DB_NAME']);
@mysqli_query($con, 'SET CHARACTER SET utf8');
@mysqli_query($con, "SET SESSION collation_connection ='utf8_general_ci'");
if (!$con) {
    die('Database Connect Error: ' . mysqli_connect_error());
}

/*
 * All helper function here
 * You can call the functions from anywhere
 * Write the description before the function
 */

/*
 * Redirect by Javascript to given link
 * @return string
 */

function redirect($link = NULL) {
    if ($link) {
        echo "<script language=Javascript>document.location.href='$link';</script>";
    } else {
        /* echo '$link does not specified'; */
    }
}

/*
 * Give your file name as suffix it will return full base path
 * @return string
 */

function base_path($suffix = '') {
    global $config;
    $suffix = ltrim($suffix, '/');
    return $config['BASE_DIR'] . '/' . trim($suffix);
}

/*
 * Give your file name as suffix it will return full base url
 * @return string
 */

function base_url($suffix = '') {
    global $config;
    $suffix = ltrim($suffix, '/');
    return $config['BASE_URL'] . trim($suffix);
}

/*
 * Check the mail is valid or not
 * @return string
 */

function is_valid_email($email = '') {
    return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email);
}

/*
 * @return string
 */

function secure_password($pass = '') {
    global $config;
    $salt_key_word = $config['PASSWORD_KEY']; /* If u want to change $saltKeyWord value first of all make the admin table empty */

    if ($pass != '') {
        $pass = md5($pass);
        /* created md5 hash */
        $length = strlen($pass);
        /* calculating the lengh of the value */
        $password_code = $salt_key_word;
        if ($password_code != '') {
            $security_code = trim($password_code);
        } else {
            $security_code = '';
        }
        /* checking set $password_code or not */
        $start = floor($length / 2);
        /* dividing the lenght */
        $search = substr($pass, 1, $start);
        /* $search = which part will replace */
        $secure_password = str_replace($search, $search . $security_code, $pass);

        /* $search.$security_code replacing a part this password_code */
        return $secure_password;
    } else {
        return '';
    }
}

/*
 * Auto creates a 6 char string [a-z A-Z 0-9]
 * @return string
 */

function passwor_generator() {
    $buchstaben = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
    $password_genarator = '';
    for ($i = 1; $i <= 6; $i++) {
        mt_srand((double) microtime() * 1000000);
        $tmp = mt_rand(0, count($buchstaben) - 1);
        $password_genarator.=$buchstaben[$tmp];
    }
    return $password_genarator;
}

/*
 * set_session function set value with custom unique session key
 * $index_name: $_SESSION['session_name']
 * $value: $_SESSION['session_name'] = $value
 * @return NULL
 */

function set_session($index_name = '', $value = '') {
    global $config;
    $index_name = trim($index_name);
    $value = trim($value);
    $_SESSION[md5($config['PASSWORD_KEY']) . '_' . $index_name] = $value;
}

/*
 * unset_ession function unset value with custom unique session key
 * $index_name: $_SESSION['session_name']
 * @return NULL
 */

function unset_session($index_name = '') {
    global $config;
    $index_name = trim($index_name);
    if (isset($_SESSION[md5($config['PASSWORD_KEY']) . '_' . $index_name])) {
        unset($_SESSION[md5($config['PASSWORD_KEY']) . '_' . $index_name]);
    }
}

/*
 * get_session function set value with custom unique session key
 * $indexName: $_SESSION['session_name']
 * @return String or boolean
 */

function get_session($index_name = '') {
    global $config;
    $index_name = trim($index_name);

    if (isset($_SESSION[md5($config['PASSWORD_KEY']) . '_' . $index_name])) {
        return $_SESSION[md5($config['PASSWORD_KEY']) . '_' . $index_name];
    } else {
        return FALSE;
    }
}

/*
 * show an array with pre tag<br/>
 * Default die false
 * @return string
 */

function debug($object) {
    echo "<pre>";
    print_r($object);
    echo "</pre>";
}

/*
 * This function will generate random number
 * @return string or number
 */

function random_code($length) {
    $random = "";
    $data = "102030405060708090";
    $data .= "090807060504030201";
    $data .= "123456789";
    $data .= "987654321";

    for ($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
}

/*
 * This removes special characters from a string
 * @return string
 */

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-_]/', '', $string); // Removes special chars.
}

/*
 * This function removes a word from last in a string
 * @return string
 */

function remove_word($string) {
    $string = explode(" ", $string);
    array_splice($string, -2);
    return implode(" ", $string);
}

/*
 * This removes special characters from a string<br>
 * @return string
 */

function validate_input($value = '') {
    $output = '';
    global $con;
    if ($value != "") {
        $output = trim($value);
        $output = strip_tags($output);
        if (is_int($output)) {
            $output = intval($output);
        } elseif (is_float($output)) {
            $output = floatval($output);
        } elseif (is_string($output)) {
            $output = mysqli_real_escape_string($con, $output);
        }
    }
    return $output;
}

/*
 * This function will short the word from last
 */

function shorten_string($string, $wordsreturned) {
    $retval = $string;
    $array = explode(" ", $string);
    if (count($array) <= $wordsreturned) {
        $retval = $string;
    } else {
        array_splice($array, $wordsreturned);
        $retval = implode(" ", $array) . " ...";
    }
    return $retval;
}

/*
 * This function will check admin login
 */

function check_admin_login() {
    $admin_id = get_session('admin_id');
    $admin_email = get_session('admin_email');
    if ($admin_id == '' || $admin_email == '') {
        $location = base_url() . "index.php";
        redirect($location);
    }
}

function check_permission() {
    $type = get_session('admin_type');
    if ($type == 'Staff') {
        $location = base_url() . "index.php";
        redirect($location);
    }
}

/*
 * Get store data
 */

function get_store_info() {
    $output = '';
    global $con;
    $sql_store_info = "SELECT * FROM store";
    $result_store_info = mysqli_query($con, $sql_store_info);
    $count_store_info = mysqli_num_rows($result_store_info);
    if ($count_store_info > 0) {
        $obj_store_info = mysqli_fetch_object($result_store_info);
        $output = $obj_store_info;
        return $output;
    } else {
        return $output;
    }
}

/*
 * Get medicine report
 */

function get_medicine_report() {
    $output = '';
    $total_medicine = 0;
    global $con;
    $sql_medicine_total = "SELECT count(*) AS total_medicine FROM medicine";
    $result_medicine_total = mysqli_query($con, $sql_medicine_total);
    $count_medicine_total = mysqli_num_rows($result_medicine_total);
    if ($count_medicine_total > 0) {
        $obj_medicine_total = mysqli_fetch_object($result_medicine_total);
        $total_medicine = $obj_medicine_total->total_medicine;
        $output = $total_medicine;
        return $output;
    } else {
        $output = $total_medicine;
        return $output;
    }
}

/*
 * Get total expense
 */

function get_today_expense() {
    $output = '';
    $today_expense = 0;
    global $con;
    $sql_today_expense = "SELECT SUM(expense_amount) AS today_expense FROM expense WHERE expense_date='" . date('Y-m-d') . "'";
    $result_today_expense = mysqli_query($con, $sql_today_expense);
    if ($result_today_expense) {
        $obj_today_expense = mysqli_fetch_object($result_today_expense);
        $today_expense = $obj_today_expense->today_expense;
        $output = $today_expense;
        return $output;
    } else {
        $output = $today_expense;
        return $output;
    }
}

/*
 * Get total Expired
 */

function get_total_expired() {
    $output = '';
    $total_expired = 0;
    global $con;
    $sql_expired_total = "SELECT count(*) AS total_expired FROM medicine "
            . "WHERE medicine_expire_date < '" . date('Y-m-d') . "'";
    $result_expired_total = mysqli_query($con, $sql_expired_total);
    $count_expired_total = mysqli_num_rows($result_expired_total);
    if ($count_expired_total > 0) {
        $obj_expired_total = mysqli_fetch_object($result_expired_total);
        $total_expired = $obj_expired_total->total_expired;
        $output = $total_expired;
        return $output;
    } else {
        $output = $total_expired;
        return $output;
    }
}

/*
 * Get today sales
 */

function get_today_sales() {
    $output = '';
    $today_sale = 0;
    global $con;
    $sql_sales_today = "SELECT SUM(order_info_total) AS today_sale FROM order_info WHERE order_info_date='" . date('Y-m-d') . "' AND order_info_status='Paid'";
    $result_sales_today = mysqli_query($con, $sql_sales_today);
    $count_sales_today = mysqli_num_rows($result_sales_today);
    if ($count_sales_today > 0) {
        $obj_sales_today = mysqli_fetch_object($result_sales_today);
        $today_sale = $obj_sales_today->today_sale;
        $output = $today_sale;
        return $output;
    } else {
        $output = $today_sale;
        return $output;
    }
}

/*
 * Get gross sale
 */

function get_gross_sale($start, $end) {
    $output = '';
    $gross_sale_amount = 0;
    global $con;
    $sql_gross_amount = "SELECT SUM(order_info_total) AS gross_sale_amount FROM order_info WHERE order_info_date BETWEEN '$start' AND '$end'";
    $result_gross_amount = mysqli_query($con, $sql_gross_amount);
    $count_gross_amount = mysqli_num_rows($result_gross_amount);
    if ($count_gross_amount > 0) {
        $obj_gross_amount = mysqli_fetch_object($result_gross_amount);
        $gross_sale_amount = $obj_gross_amount->gross_sale_amount;
        $output = $gross_sale_amount;
        return $output;
    } else {
        $output = $gross_sale_amount;
        return $output;
    }
}

/*
 * Get gross expense
 */

function get_gross_expense($start, $end) {
    $output = '';
    $gross_expense = 0;
    global $con;
    $sql_gross_expense = "SELECT SUM(expense_amount) AS gross_expense FROM expense WHERE expense_date BETWEEN '$start' AND '$end'";
    $result_gross_expense = mysqli_query($con, $sql_gross_expense);
    $count_gross_expense = mysqli_num_rows($result_gross_expense);
    if ($count_gross_expense > 0) {
        $obj_gross_expense = mysqli_fetch_object($result_gross_expense);
        $gross_expense = $obj_gross_expense->gross_expense;
        $output = $gross_expense;
        return $output;
    } else {
        $output = $gross_expense;
        return $output;
    }
}

/*
 * Get notification
 */

function get_notification() {
    $output = '';
    $notification = array();
    $count_notification = 0;
    global $con;
    $sql_notification = "SELECT medicine.medicine_name,medicine.medicine_quantity,medicine.medicine_expire_date FROM medicine WHERE medicine_quantity <= 10 OR medicine_expire_date < '" . date('Y-m-d') . "'";
    $result_notification = mysqli_query($con, $sql_notification);
    $count_notification = mysqli_num_rows($result_notification);
    if ($count_notification > 0) {
        while ($obj_notifiation = mysqli_fetch_object($result_notification)) {
            $notification[] = $obj_notifiation;
        }
        $output = $notification;
        return $output;
    } else {
        $output = $count_notification;
        return $output;
    }
}

/*
 * Get gross profit based on date in report
 */

function get_gross_profit($start, $end) {
    $output = '';
    $gross_profit = 0;
    global $con;
    $sql_gross_profit = "SELECT SUM(order_details_medicine_profit) AS gross_profit FROM order_details"
            . " WHERE order_details_date BETWEEN '$start' AND '$end'";
    $result_gross_profit = mysqli_query($con, $sql_gross_profit);
    $count_gross_profit = mysqli_num_rows($result_gross_profit);
    if ($count_gross_profit > 0) {
        $obj_gross_profit = mysqli_fetch_object($result_gross_profit);
        $gross_profit = $obj_gross_profit->gross_profit;
        $output = $gross_profit;
        return $output;
    } else {
        $output = $gross_profit;
        return $output;
    }
}

?>
