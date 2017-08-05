<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$page_id = 1;
$store_info = get_store_info();
$medicine_id = $medicine_name = $medicine_category = $medicine_company = $medicine_quantity = $medicine_rack_no = $medicine_buy_price = $medicine_sell_price = $medicine_expire_date = "";
$medicine_updated_by = get_session('admin_id');

if (isset($_GET['id'])) {
    $medicine_id = base64_decode($_GET['id']);
}

if (isset($_POST['btn_update_medicine'])) {
    extract($_POST);
    $medicine_id = validate_input($medicine_id);
    $medicine_name = validate_input($medicine_name);
    $medicine_category = validate_input($medicine_category);
    $medicine_buy_price = validate_input($medicine_buy_price);
    $medicine_sell_price = validate_input($medicine_sell_price);
    $medicine_rack_no = validate_input($medicine_rack_no);
    $medicine_quantity = validate_input($medicine_quantity);
    $medicine_company = validate_input($medicine_company);
    $medicine_expire_date = validate_input($medicine_expire_date);

    $sql_check = "SELECT * FROM medicine WHERE medicine_name='$medicine_name' AND medicine_id NOT IN (" . $medicine_id . ")";
    $result_check = mysqli_query($con, $sql_check);
    $count_check = mysqli_num_rows($result_check);
    if ($count_check > 0) {
        $error = "Medicine name already exists";
    } else {
        $variable = '';
        $variable .= 'medicine_name = "' . $medicine_name . '"';
        $variable .= ',medicine_category = "' . $medicine_category . '"';
        $variable .= ',medicine_buy_price = "' . $medicine_buy_price . '"';
        $variable .= ',medicine_sell_price = "' . $medicine_sell_price . '"';
        $variable .= ',medicine_rack_no = "' . $medicine_rack_no . '"';
        $variable .= ',medicine_quantity = "' . $medicine_quantity . '"';
        $variable .= ',medicine_company = "' . $medicine_company . '"';
        $variable .= ',medicine_expire_date = "' . $medicine_expire_date . '"';
        $variable .= ',medicine_updated_by = "' . $medicine_updated_by . '"';

        $sql_update_medicine = "UPDATE medicine SET $variable WHERE medicine_id=$medicine_id";
        $result_update_medicine = mysqli_query($con, $sql_update_medicine);
        if ($result_update_medicine) {
            $success = "Medicine updated successfully";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
/*
 * Getting medicine data
 */
$sql_data = "SELECT * FROM medicine WHERE medicine_id=$medicine_id";
$result_data = mysqli_query($con, $sql_data);
if ($result_data) {
    $obj = mysqli_fetch_object($result_data);
    $medicine_name = $obj->medicine_name;
    $medicine_category = $obj->medicine_category;
    $medicine_buy_price = $obj->medicine_buy_price;
    $medicine_sell_price = $obj->medicine_sell_price;
    $medicine_quantity = $obj->medicine_quantity;
    $medicine_rack_no = $obj->medicine_rack_no;
    $medicine_company = $obj->medicine_company;
    $medicine_expire_date = $obj->medicine_expire_date;
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
                                    <h5><i class="fa fa-edit"></i>&nbsp;Edit Medicine</h5>
                                </div>
                                <div class="ibox-content">
                                    <form role="form"  method="POST" action="">
                                        <input type="hidden" name="medicine_id" value="<?php echo $medicine_id; ?>" />
                                        <div class="form-group">
                                            <label for="medicine_name">Name<b class="required_mark">*</b></label>
                                            <input name="medicine_name" class="form-control" type="text" value="<?php echo $medicine_name; ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_category">Category<b class="required_mark">*</b></label>
                                            <select class="form-control" name="medicine_category">
                                                <option value="">-- Select Category --</option>
                                                <?php
                                                /*
                                                 * getting medicine category
                                                 */
                                                $sql_get_category = "SELECT medicine_category_id,medicine_category_name FROM medicine_category WHERE medicine_category_status='Active'";
                                                $result_get_category = mysqli_query($con, $sql_get_category);
                                                ?>
                                                <?php if (count($result_get_category) > 0): ?>
                                                    <?php while ($obj_category = mysqli_fetch_object($result_get_category)): ?>
                                                        <option value="<?php echo $obj_category->medicine_category_id; ?>"<?php
                                                        if ($obj_category->medicine_category_id == $medicine_category) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $obj_category->medicine_category_name; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_buy_price">Purchase Price<b class="required_mark">*</b></label>
                                            <input type="text" name="medicine_buy_price" value="<?php echo $medicine_buy_price; ?>" class="form-control" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_sell_price">Selling Price<b class="required_mark">*</b></label>
                                            <input type="text" name="medicine_sell_price" value="<?php echo $medicine_sell_price; ?>" class="form-control" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_rack_no">Store Rack Number</label>
                                            <input type="text" name="medicine_rack_no" value="<?php echo $medicine_rack_no; ?>" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_quantity">Quantity<b class="required_mark">*</b></label>
                                            <input type="text" name="medicine_quantity" value="<?php echo $medicine_quantity; ?>" class="form-control" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_company">Company Name</label>
                                            <input type="text" name="medicine_company" value="<?php echo $medicine_company; ?>" class="form-control" />
                                        </div>
                                        <div class="form-group" id="expire_date">
                                            <label for="medicine_expire_date">Expire Date</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" name="medicine_expire_date" value="<?php echo $medicine_expire_date; ?>" type="text">
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit" name="btn_update_medicine"><i class="fa fa-check"></i> Submit</button>
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
        <script>
            $('#expire_date .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                format: "yyyy-mm-dd"
            });
        </script>
        <script type="text/javascript">
            $("#menu6").addClass("active");
            $("#menu6").parent().parent().addClass("treeview active");
            $("#menu6").parent().addClass("in");
        </script>
    </body>
</html>
