<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$medicine_category_id = '';
$medicine_category_name = '';
$medicine_category_parent_id = '';
$medicine_category_status = '';
$medicine_category_updated_by = get_session('admin_id');

if (isset($_GET['id'])) {
    $medicine_category_id = base64_decode($_GET['id']);
}

if (isset($_POST['btn_edit_category'])) {
    extract($_POST);
    $medicine_category_name = validate_input($medicine_category_name);
    $medicine_category_parent_id = validate_input($medicine_category_parent_id);
    $medicine_category_status = validate_input($medicine_category_status);

    $check_category_sql = "SELECT * FROM medicine_category WHERE medicine_category_name = '$medicine_category_name' AND medicine_category_id NOT IN (" . $medicine_category_id . ")";
    $result_category = mysqli_query($con, $check_category_sql);
    $count_category = mysqli_num_rows($result_category);
    if ($count_category >= 1) {
        $error = "Medicine category name already exists";
    } else {
        $variable = '';
        $variable .= 'medicine_category_name = "' . $medicine_category_name . '"';
        $variable .= ',medicine_category_parent_id = "' . $medicine_category_parent_id . '"';
        $variable .= ',medicine_category_status = "' . $medicine_category_status . '"';
        $variable .= ',medicine_category_updated_by = "' . $medicine_category_updated_by . '"';
        $sql_update_category = "UPDATE medicine_category SET $variable WHERE medicine_category_id = $medicine_category_id";
        $result_update_category = mysqli_query($con, $sql_update_category);
        if ($result_update_category) {
            $success = "Medicine category updated successfully";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

/*
 * Getting data
 */
$sql_data = "SELECT * FROM medicine_category WHERE medicine_category_id = $medicine_category_id";
$result_data = mysqli_query($con, $sql_data);
if ($result_data) {
    $obj = mysqli_fetch_object($result_data);
    $medicine_category_name = $obj->medicine_category_name;
    $medicine_category_parent_id = $obj->medicine_category_parent_id;
    $medicine_category_status = $obj->medicine_category_status;
} else {
    $error = "Data not found";
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
                                    <h5><i class="fa fa-edit"></i>&nbsp;Edit Medicine Category</h5>
                                </div>
                                <div class="ibox-content">
                                    <form role="form"  method="POST" action="">
                                        <div class="form-group">
                                            <label for="medicine_category_name">Category Name<b class="required_mark">*</b></label>
                                            <input name="medicine_category_name" class="form-control" type="text" value="<?php echo $medicine_category_name; ?>" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_category_parent_id">Parent Category<b class="required_mark">*</b></label>
                                            <select class="form-control" name="medicine_category_parent_id">
                                                <option value="0">Root</option>
                                                <?php
                                                /*
                                                 * getting medicine category
                                                 */
                                                $sql_get_category = "SELECT medicine_category_id,medicine_category_name FROM medicine_category WHERE medicine_category_status='Active'";
                                                $result_get_category = mysqli_query($con, $sql_get_category);
                                                ?>
                                                <?php if (count($result_get_category) > 0): ?>
                                                    <?php while ($obj_category = mysqli_fetch_object($result_get_category)): ?>
                                                        <option value="<?php echo $obj_category->medicine_category_id; ?>"
                                                        <?php
                                                        if ($obj_category->medicine_category_id == $medicine_category_parent_id) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $obj_category->medicine_category_name; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="medicine_category_status">Status<b class="required_mark">*</b></label>
                                            <select class="form-control" name="medicine_category_status" id="medicine_category_status" required>
                                                <option value="Active"<?php
                                                if ($medicine_category_status === 'Active') {
                                                    echo "selected";
                                                }
                                                ?>>Active</option>
                                                <option value="Inactive"<?php
                                                if ($medicine_category_status === 'Inactive') {
                                                    echo "selected";
                                                }
                                                ?>>Inactive</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary" type="submit" name="btn_edit_category"><i class="fa fa-check"></i> Submit</button>
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
            $("#menu3").addClass("active");
            $("#menu3").parent().parent().addClass("treeview active");
            $("#menu3").parent().addClass("in");
        </script>
    </body>
</html>
