<?php

include './config/config.php';
$sql = "SELECT * FROM admin";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);

$sql_store = "SELECT * FROM store";
$result_store = mysqli_query($con, $sql_store);
$count_store = mysqli_num_rows($result_store);
if ($count > 0 && $count_store > 0) {
    /*
     * Redirect to login page
     */
    $link = base_url() . "login.php";
    redirect($link);
} else {
    /*
     * Action required to system settings
     */
    if ($count_store > 0) {
        $link = base_url() . "login.php";
        redirect($link);
    } elseif ($count > 0) {
        $link = base_url() . "step_two.php";
        redirect($link);
    } else {
        $link = base_url() . "step_one.php";
        redirect($link);
    }
}
?>
