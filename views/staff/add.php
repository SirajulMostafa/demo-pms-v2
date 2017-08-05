<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$admin_name = $admin_email = $admin_username = $admin_password = $admin_confirm_password = $admin_phone = $admin_address = $admin_status = $admin_type = "";
$admin_created_by = get_session('admin_id');
$admin_created_on = date('Y-m-d H:i:s');

if (isset($_POST['btn_save_staff'])) {
    extract($_POST);

    $admin_name = validate_input($admin_name);
    $admin_email = validate_input($admin_email);
    $admin_phone = validate_input($admin_phone);
    $admin_address = validate_input($admin_address);
    $admin_type = validate_input($admin_type);
    $admin_status = validate_input($admin_status);
    $admin_username = validate_input($admin_username);
    $admin_password = validate_input($admin_password);
    $admin_confirm_password = validate_input($admin_confirm_password);

    /*
     * Checking password and confirm password
     */
    if ($admin_password != $admin_confirm_password) {
        $error = "Password not matched";
    } else {
        $sql_check = "SELECT * FROM admin WHERE admin_email='$admin_email'";
        $result_check = mysqli_query($con, $sql_check);
        $count_check = mysqli_num_rows($result_check);
        if ($count_check > 0) {
            $error = "An user already exists using this email address";
        } else {
            /*
             * Make secure password
             */
            $admin_password = secure_password($admin_password);
            $variable = '';
            $variable .= 'admin_name = "' . $admin_name . '"';
            $variable .= ',admin_email = "' . $admin_email . '"';
            $variable .= ',admin_username = "' . $admin_username . '"';
            $variable .= ',admin_password = "' . $admin_password . '"';
            $variable .= ',admin_status = "' . $admin_status . '"';
            $variable .= ',admin_phone = "' . $admin_phone . '"';
            $variable .= ',admin_address = "' . $admin_address . '"';
            $variable .= ',admin_type = "' . $admin_type . '"';
            $variable .= ',admin_created_by = "' . $admin_created_by . '"';
            $variable .= ',admin_created_on = "' . $admin_created_on . '"';

            $sql = "INSERT INTO admin SET $variable";
            $result = mysqli_query($con, $sql);
            if ($result) {
                $success = "Staff saved successfully";
                $admin_name = $admin_email = $admin_username = $admin_password = $admin_confirm_password = $admin_phone = $admin_address = $admin_status = $admin_type = "";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $store_info->store_title; ?></title>
        <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/animate.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/style.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/custom.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/plugins/datapicker/datepicker3.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <div id="wrapper">
            <?php include base_path('left_menu.php'); ?>
            <div id="page-wrapper" class="gray-bg">
                <?php include base_path('top_bar.php'); ?>
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <?php if ($error): ?>
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($success): ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $success; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="ibox-title">
                                    <h5><i class="fa fa-plus"></i>&nbsp;Add New Staff</h5>
                                </div>
                                <div class="ibox-content">
                                    <form role="form"  method="POST" action="">
                                        <div class="form-group">
                                            <label for="admin_name">Name<b class="required_mark">*</b></label>
                                            <input type="text" class="form-control" name="admin_name" value="<?php echo $admin_name; ?>" placeholder="Enter name" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="admin_email">Email<b class="required_mark">*</b></label>
                                            <input type="email" class="form-control" name="admin_email" value="<?php echo $admin_email; ?>" placeholder="Enter email" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="admin_phone">Phone</label>
                                            <input type="text" maxlength="15" class="form-control" name="admin_phone" value="<?php echo $admin_phone; ?>" placeholder="Enter phone" />
                                        </div>
                                        <div class="form-group">
                                            <label for="admin_address">Address</label>
                                            <textarea class="form-control" name="admin_address" style="resize: vertical"><?php echo $admin_address; ?></textarea>
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
                                        <div class="form-group">
                                            <label for="admin_type">Type<b class="required_mark">*</b></label>
                                            <select class="form-control" name="admin_type" required>
                                                <option value="">--</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Staff">Staff</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="admin_status">Status<b class="required_mark">*</b></label>
                                            <select class="form-control" name="admin_status" required>
                                                <option value="">--</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" type="submit" name="btn_save_staff"><i class="fa fa-check"></i> Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include base_path('footer.php'); ?>
            </div>
        </div>
        <script src="<?php echo base_url('public/js/jquery.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/inspinia.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/plugins/pace/pace.min.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>

        <script type="text/javascript">
            $("#menu12").addClass("active");
            $("#menu12").parent().parent().addClass("treeview active");
            $("#menu12").parent().addClass("in");
        </script>
    </body>
</html>
