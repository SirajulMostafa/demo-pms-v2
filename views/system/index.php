<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$store_title = $store_info->store_title;
$store_name = $store_info->store_name;
$store_address = $store_info->store_address;
$store_email = $store_info->store_email;
$store_phone = $store_info->store_phone;
$store_currency = $store_info->store_currency;
$store_discount_type = $store_info->store_discount_type;
$store_id = $store_info->store_id;
if (isset($_POST['btn_update_store_info'])) {
    extract($_POST);
    $store_id = validate_input($store_id);
    $store_name = validate_input($store_name);
    $store_title = validate_input($store_title);
    $store_email = validate_input($store_email);
    $store_phone = validate_input($store_phone);
    $store_address = validate_input($store_address);
    $store_currency = validate_input($store_currency);
    $store_discount_type = validate_input($store_discount_type);

    $variable = '';
    $variable .= 'store_name = "' . $store_name . '"';
    $variable .= ',store_title = "' . $store_title . '"';
    $variable .= ',store_email = "' . $store_email . '"';
    $variable .= ',store_phone = "' . $store_phone . '"';
    $variable .= ',store_address = "' . $store_address . '"';
    $variable .= ',store_currency = "' . $store_currency . '"';
    $variable .= ',store_discount_type = "' . $store_discount_type . '"';

    $sql_update_store_info = "UPDATE store SET $variable WHERE store_id=$store_id";
    $result_update_store_info = mysqli_query($con, $sql_update_store_info);
    if ($result_update_store_info) {
        $success = "Store information updated successfully";
    } else {
        $error = "Something went wrong. Please try again.";
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
                                    <h5><i class="fa fa-plus"></i>&nbsp;Store Information</h5>
                                </div>
                                <div class="ibox-content">
                                    <form role="form" method="POST" action="">
                                        <input type="hidden" name="store_id" value="<?php echo $store_id; ?>" />
                                        <div class="form-group">
                                            <label for="store_title">Store Title<b class="required_mark">*</b></label>
                                            <input type="text" class="form-control" name="store_title" value="<?php echo $store_title; ?>" placeholder="Enter title" required />
                                            <small><i>eg. XYZ Management System</i></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="store_name">Store Name<b class="required_mark">*</b></label>
                                            <input type="text" class="form-control" name="store_name" value="<?php echo $store_name; ?>" placeholder="Enter name" required />
                                            <small><i>eg. Your store name (ABC or XYZ)</i></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="store_email">Store Email</label>
                                            <input type="email" class="form-control" name="store_email" value="<?php echo $store_email; ?>" placeholder="Enter email" />
                                        </div>
                                        <div class="form-group">
                                            <label for="store_phone">Store Phone</label>
                                            <input type="text" maxlength="15" class="form-control" name="store_phone" value="<?php echo $store_phone; ?>" placeholder="Enter phone" />
                                        </div>
                                        <div class="form-group">
                                            <label for="store_address">Store Address</label>
                                            <textarea class="form-control" name="store_address" style="resize: vertical;" placeholder="Enter address"><?php echo $store_address; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="store_currency">Store Currency<b class="required_mark">*</b></label>
                                            <select class="form-control" name="store_currency" required>
                                                <option value="">--</option>
                                                <option value="Taka"<?php
                                                if ($store_currency == 'Taka') {
                                                    echo "selected";
                                                }
                                                ?>>Taka</option>
                                                <option value="Dollar"<?php
                                                if ($store_currency == 'Dollar') {
                                                    echo "selected";
                                                }
                                                ?>>Dollar</option>
                                                <option value="Euro"<?php
                                                if ($store_currency == 'Euro') {
                                                    echo "selected";
                                                }
                                                ?>>Euro</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="store_discount_type">Store Discount Type<b class="required_mark">*</b></label>
                                            <select class="form-control" name="store_discount_type" required>
                                                <option value="">--</option>
                                                <option value="Flat"<?php
                                                if ($store_discount_type == 'Flat') {
                                                    echo "selected";
                                                }
                                                ?>>Flat</option>
                                                <option value="Percent"<?php
                                                if ($store_discount_type == 'Percent') {
                                                    echo "selected";
                                                }
                                                ?>>Percent (%)</option>
                                            </select>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label></label>
                                            <button class="btn btn-primary pull-right" type="submit" name="btn_update_store_info"><i class="fa fa-check"></i> Save</button>
                                        </div>
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
            $("#menu2").addClass("active");
            $("#menu2").parent().parent().addClass("treeview active");
            $("#menu2").parent().addClass("in");
        </script>
    </body>
</html>
