<?php
include './config/config.php';
$sql_store = "SELECT * FROM store";
$result_store = mysqli_query($con, $sql_store);
$count_store = mysqli_num_rows($result_store);
if ($count_store > 0) {
    $link = base_url() . "login.php";
    redirect($link);
}
$store_name = $store_title = $store_email = $store_phone = $store_address = $store_currency = $store_discount_type = "";
if (isset($_POST['btn_save_step_two'])) {
    extract($_POST);
    $store_name = validate_input($store_name);
    $store_title = validate_input($store_title);
    $store_email = validate_input($store_email);
    $store_phone = validate_input($store_phone);
    $store_address = validate_input($store_address);
    $store_currency = validate_input($store_currency);
    $store_discount_type = validate_input($store_discount_type);


    /*
     * Save data and redirect to login page
     */
    $variable = '';
    $variable .= 'store_name = "' . $store_name . '"';
    $variable .= ',store_title = "' . $store_title . '"';
    $variable .= ',store_email = "' . $store_email . '"';
    $variable .= ',store_phone = "' . $store_phone . '"';
    $variable .= ',store_address = "' . $store_address . '"';
    $variable .= ',store_currency = "' . $store_currency . '"';
    $variable .= ',store_discount_type = "' . $store_discount_type . '"';
    $variable .= ',store_created_on = "' . date('Y-m-d H:i:s') . '"';

    $sql = "INSERT INTO store SET $variable";
    $result = mysqli_query($con, $sql);
    if ($result) {
        /*
         * Redirect to login page
         */
        $link = base_url() . "login.php";
        redirect($link);
    } else {
        $error = "Something went wrong. Please try again";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pharmacy Management System | Store Information</title>
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
                            <h5>Hello, Provide Store Information</h5>
                        </div>
                        <div class="ibox-content">
                            <small class="">Provide store information. This information will be use in invoices. Make sure the currency.Make sure all provided information is correct.</small>
                            <form role="form" method="POST" action="">

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
                                        <option value="Taka">Taka</option>
                                        <option value="Dollar">Dollar</option>
                                        <option value="Euro">Euro</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="store_discount_type">Store Discount Type<b class="required_mark">*</b></label>
                                    <select class="form-control" name="store_discount_type" required>
                                        <option value="">--</option>
                                        <option value="Flat">Flat</option>
                                        <option value="Percent">Percent (%)</option>
                                    </select>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label></label>
                                    <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit" name="btn_save_step_two"><strong>Save</strong></button>
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
