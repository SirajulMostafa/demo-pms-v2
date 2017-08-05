<?php
include './config/config.php';
$sql = "SELECT * FROM admin";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);
if ($count > 0) {
    $link = base_url() . "step_two.php";
    redirect($link);
}
$admin_name = $admin_email = $admin_username = $admin_password = $admin_confirm_password = "";
if (isset($_POST['btn_save_step_one'])) {
    extract($_POST);
    $admin_name = validate_input($admin_name);
    $admin_email = validate_input($admin_email);
    $admin_username = validate_input($admin_username);
    $admin_password = validate_input($admin_password);
    $admin_confirm_password = validate_input($admin_confirm_password);

    /*
     * Checking password and confirm password
     */
    if ($admin_password != $admin_confirm_password) {
        $error = "Password not matched";
    } else {
        /*
         * Make secure password
         */
        $admin_password = secure_password($admin_password);
        $admin_type = $admin_status = $admin_created_by = 1;
        $admin_created_on = date('Y-m-d H:i:s');
        /*
         * Save data and make an admin
         * Go to step two as system settings
         */
        $variable = '';
        $variable .= 'admin_name = "' . $admin_name . '"';
        $variable .= ',admin_email = "' . $admin_email . '"';
        $variable .= ',admin_username = "' . $admin_username . '"';
        $variable .= ',admin_password = "' . $admin_password . '"';
        $variable .= ',admin_status = "' . $admin_status . '"';
        $variable .= ',admin_type = "' . $admin_type . '"';
        $variable .= ',admin_created_by = "' . $admin_created_by . '"';
        $variable .= ',admin_created_on = "' . $admin_created_on . '"';

        $sql = "INSERT INTO admin SET $variable";
        $result = mysqli_query($con, $sql);
        if ($result) {
            /*
             * Redirect to system settings page ... step two
             */
            $link = base_url() . "step_two.php";
            redirect($link);
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Account Information</title>
        <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/animate.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/style.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/custom.css'); ?>" rel="stylesheet">
    </head>
    <body class="gray-bg">
        <div class="wrapper wrapper-content animated fadeIn">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="ibox float-e-margins">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <div class="ibox-title">
                            <h5>Welcome, Provide Account Information</h5>
                        </div>
                        <div class="ibox-content">
                            <small class="">This account will be considered as a system super admin account. Make sure all provided information is correct.</small>
                            <form role="form" method="POST" action="">
                                <div class="form-group">
                                    <label for="admin_name">Name<b class="required_mark">*</b></label>
                                    <input type="text" class="form-control" name="admin_name" value="<?php echo $admin_name; ?>" placeholder="Enter name" required />
                                </div>
                                <div class="form-group">
                                    <label for="admin_email">Email<b class="required_mark">*</b></label>
                                    <input type="email" class="form-control" name="admin_email" value="<?php echo $admin_email; ?>" placeholder="Enter email" required />
                                </div>
                                <div class="form-group">
                                    <label for="admin_username">Username<b class="required_mark">*</b></label>
                                    <input type="text" class="form-control" name="admin_username" value="<?php echo $admin_username; ?>" placeholder="Enter username" required />
                                    <small>Remember your username</small>
                                </div>
                                <div class="form-group">
                                    <label for="admin_password">Password<b class="required_mark">*</b></label>
                                    <input type="password" class="form-control" name="admin_password" value="<?php echo $admin_password; ?>" placeholder="Enter password" required />
                                    <small>Remember your password</small>
                                </div>
                                <div class="form-group">
                                    <label for="admin_confirm_password">Confirm Password<b class="required_mark">*</b></label>
                                    <input type="password" class="form-control" name="admin_confirm_password" value="<?php echo $admin_confirm_password; ?>" placeholder="Enter confirm password" required />
                                </div>
                                <br>
                                <div class="form-group">
                                    <label></label>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="btn_save_step_one"><strong>Save & Next</strong></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url('public/js/jquery.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>"></script>
    </body>
</html>
