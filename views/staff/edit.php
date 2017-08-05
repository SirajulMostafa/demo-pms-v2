<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$page_id = 2;
$admin_id = '';
$admin_updated_by = get_session('admin_id');

$admin_name = $admin_email = $admin_phone = $admin_address = $admin_status = $admin_type = "";
if (isset($_GET['id'])) {
    $admin_id = base64_decode($_GET['id']);
}

if (isset($_POST['btn_edit_staff'])) {
    extract($_POST);
    $admin_id = validate_input($admin_id);
    $admin_name = validate_input($admin_name);
    $admin_email = validate_input($admin_email);
    $admin_phone = validate_input($admin_phone);
    $admin_address = validate_input($admin_address);
    $admin_type = validate_input($admin_type);
    $admin_status = validate_input($admin_status);

    $check_sql = "SELECT * FROM admin WHERE admin_email = '$admin_email' AND admin_id NOT IN (" . $admin_id . ")";
    $result_check = mysqli_query($con, $check_sql);
    $count_check = mysqli_num_rows($result_check);
    if ($count_check >= 1) {
        $error = "A staff already exists using given email address";
    } else {
        $variable = '';
        $variable .= 'admin_name = "' . $admin_name . '"';
        $variable .= ',admin_email = "' . $admin_email . '"';
        $variable .= ',admin_status = "' . $admin_status . '"';
        $variable .= ',admin_phone = "' . $admin_phone . '"';
        $variable .= ',admin_address = "' . $admin_address . '"';
        $variable .= ',admin_type = "' . $admin_type . '"';
        $variable .= ',admin_updated_by = "' . $admin_updated_by . '"';

        $sql_update_staff = "UPDATE admin SET $variable WHERE admin_id = $admin_id";
        $result_update_staff = mysqli_query($con, $sql_update_staff);
        if ($result_update_staff) {
            $success = "Staff information updated successfully";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

/*
 * Getting staff info
 */
if ($admin_id != '' && $admin_id > 0) {
    $sql_get_data = "SELECT * FROM admin WHERE admin_id=$admin_id";
    $result_get_data = mysqli_query($con, $sql_get_data);
    if ($result_get_data) {
        $obj_get_data = mysqli_fetch_object($result_get_data);
        $admin_name = $obj_get_data->admin_name;
        $admin_email = $obj_get_data->admin_email;
        $admin_phone = $obj_get_data->admin_phone;
        $admin_address = $obj_get_data->admin_address;
        $admin_status = $obj_get_data->admin_status;
        $admin_type = $obj_get_data->admin_type;
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
        <link href="<?php echo base_url('public/css/plugins/footable/footable.core.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <div id="wrapper">
            <?php include base_path('left_menu.php'); ?>
            <div id="page-wrapper" class="gray-bg">
                <?php include base_path('top_bar.php'); ?>
                <div class="wrapper wrapper-content animated fadeInRight">

                    <div class="row">
                        <div class="col-lg-6">
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
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Edit Staff Info</h5>
                                </div>
                                <div class="ibox-content">
                                    <form role="form"  method="POST" action="">
                                        <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>" />
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
                                            <label for="admin_type">Type<b class="required_mark">*</b></label>
                                            <select class="form-control" name="admin_type" required>
                                                <option value="">--</option>
                                                <option value="Admin"<?php
                                                if ($admin_type == 'Admin') {
                                                    echo "selected";
                                                }
                                                ?>>Admin</option>
                                                <option value="Staff"<?php
                                                if ($admin_type == 'Staff') {
                                                    echo "selected";
                                                }
                                                ?>>Staff</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="admin_status">Status<b class="required_mark">*</b></label>
                                            <select class="form-control" name="admin_status" required>
                                                <option value="">--</option>
                                                <option value="Active"<?php
                                                if ($admin_status == 'Active') {
                                                    echo "selected";
                                                }
                                                ?>>Active</option>
                                                <option value="Inactive"<?php
                                                if ($admin_status == 'Inactive') {
                                                    echo "selected";
                                                }
                                                ?>>Inactive</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" type="submit" name="btn_edit_staff"><i class="fa fa-check"></i> Submit</button>
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

        <script type="text/javascript">
            $("#menu13").addClass("active");
            $("#menu13").parent().parent().addClass("treeview active");
            $("#menu13").parent().addClass("in");
        </script>
    </body>
</html>
