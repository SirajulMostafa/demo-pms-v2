<?php
include './config/config.php';
$store_info = get_store_info();
$admin_username = '';
$admin_password = '';

if (isset($_POST['btn_login'])) {
    extract($_POST);
    $admin_username = validate_input($admin_username);
    $admin_password = validate_input($admin_password);

    /*
     * Checking username and password exists or not
     */
    $admin_password = secure_password($admin_password);
    $sql_check = "SELECT * FROM admin WHERE admin_username='$admin_username' AND admin_password='$admin_password' AND admin_status='Active'";
    $result_check = mysqli_query($con, $sql_check);
    $count_check = mysqli_num_rows($result_check);
    if ($count_check > 0) {
        $obj_check = mysqli_fetch_object($result_check);


        /*
         * Complete login 
         * Set session infomation
         */
        set_session("admin_id", $obj_check->admin_id);
        set_session("admin_username", $admin_username);
        set_session("admin_email", $obj_check->admin_email);
        set_session("admin_name", $obj_check->admin_name);
        set_session("admin_type", $obj_check->admin_type);

        /*
         * Redirect to dashboard
         */
        if ($obj_check->admin_type == 'Admin') {
            $link = base_url() . "views/dashboard/index.php";
            redirect($link);
        } else {
            $link = base_url() . "views/pos/index.php";
            redirect($link);
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pharmacy Management System | Login</title>
        <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/animate.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/style.css'); ?>" rel="stylesheet">
    </head>
    <body class="gray-bg">
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div>
                <h1 class="logo-name"><img src="public/images/logo.png" width="200px" /></h1>
            </div>
            <h3>Welcome to <?php echo $store_info->store_name; ?></h3>
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form  role="form" action="" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" name="admin_username" value="<?php echo $admin_username; ?>" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="admin_password" placeholder="Password" required />
                </div>
                <button type="submit" name="btn_login" class="btn btn-primary block full-width">Login</button>
                <a href="#"><small>Forgot password?</small></a>
            </form>
            <p> 
                <small><?php echo $store_info->store_title; ?> - Version 1.0.0 &copy; <?php echo date('Y'); ?></small> 
            </p>
            <a href="http://www.uitsbd.com"><small>Developed By: Untitled IT Solutions</small></a>
        </div>
        <script src="<?php echo base_url('public/js/jquery.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>"></script>
    </body>
</html>
