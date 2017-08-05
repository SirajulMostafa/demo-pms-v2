<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
/*
 * Delete medicine category
 */
$medicine_category_id = '';
if (isset($_POST['btn_delete_medicine_category'])) {
    extract($_POST);
    $medicine_category_id = validate_input($medicine_category_id);
    if ($medicine_category_id > 0 && $medicine_category_id != '') {
        $sql_delete_medicine_category = "DELETE FROM medicine_category WHERE medicine_category_id=$medicine_category_id";
        $result_delete_medicine_category = mysqli_query($con, $sql_delete_medicine_category);
        if ($result_delete_medicine_category) {
            $success = "Medicine category deleted successfully";
        } else {
            $error = "Something went wrong";
        }
    }
}


$medicine_category_name = '';
$medicine_category_parent_id = '';
$medicine_category_status = '';
$medicine_category_created_on = date('Y-m-d H:i:s');
$medicine_category_created_by = get_session('admin_id');

if (isset($_POST['btn_save_category'])) {
    extract($_POST);
    $medicine_category_name = validate_input($medicine_category_name);
    $medicine_category_parent_id = validate_input($medicine_category_parent_id);
    $medicine_category_status = validate_input($medicine_category_status);

    $check_category_sql = "SELECT * FROM medicine_category WHERE medicine_category_name = '$medicine_category_name'";
    $result_category = mysqli_query($con, $check_category_sql);
    $count_category = mysqli_num_rows($result_category);
    if ($count_category >= 1) {
        $error = "Medicine category name already exists";
    } else {
        $variable = '';
        $variable .= 'medicine_category_name = "' . $medicine_category_name . '"';
        $variable .= ',medicine_category_parent_id = "' . $medicine_category_parent_id . '"';
        $variable .= ',medicine_category_status = "' . $medicine_category_status . '"';
        $variable .= ',medicine_category_created_by = "' . $medicine_category_created_by . '"';
        $variable .= ',medicine_category_created_on = "' . $medicine_category_created_on . '"';
        $sql_insert_category = "INSERT INTO medicine_category SET $variable";
        $result_insert_category = mysqli_query($con, $sql_insert_category);
        if ($result_insert_category) {
            $success = "Medicine category saved successfully";
            $medicine_category_name = "";
        } else {
            $error = "Something went wrong. Please try again.";
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
        <link href="<?php echo base_url('public/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <div id="wrapper">
            <?php include base_path('left_menu.php'); ?>
            <div id="page-wrapper" class="gray-bg">
                <?php include base_path('top_bar.php'); ?>
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
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
                                    <h5><i class="fa fa-plus"></i>&nbsp;Add New Medicine Category</h5>
                                </div>
                                <div class="ibox-content">
                                    <form role="form" class="form-inline" method="POST" action="">
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
                                                        <option value="<?php echo $obj_category->medicine_category_id; ?>"><?php echo $obj_category->medicine_category_name; ?></option>
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
                                        <div class="form-group">
                                            <button style="margin-top: 5px;" class="btn btn-primary" type="submit" name="btn_save_category"><i class="fa fa-check"></i> Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Medicine Category List</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                                            <thead>
                                                <tr>
                                                    <th>Category Name</th>
                                                    <th>Parent Category</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                /*
                                                 * Getting medicine category data
                                                 */

                                                $sqlList = "SELECT cat1.medicine_category_id, cat1.medicine_category_name, cat1.medicine_category_status,"
                                                        . "cat2.medicine_category_id AS medicine_category_parent_id, cat2.medicine_category_name AS medicine_category_parent_name "
                                                        . "FROM medicine_category AS cat1 "
                                                        . "LEFT JOIN medicine_category AS cat2 ON cat1.medicine_category_parent_id = cat2.medicine_category_id "
                                                        . "ORDER BY cat1.medicine_category_id DESC";
                                                $resultList = mysqli_query($con, $sqlList);
                                                ?>
                                                <?php while ($obj = mysqli_fetch_object($resultList)): ?>
                                                    <tr>
                                                        <td><?php echo $obj->medicine_category_name; ?></td>
                                                        <td>
                                                            <?php if ($obj->medicine_category_parent_id != ''): ?>
                                                                <?php echo $obj->medicine_category_parent_name; ?>
                                                            <?php else: ?>
                                                                <?php echo "Root"; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($obj->medicine_category_status == 'Active'): ?>
                                                                <span class="btn-primary btn-xs"><?php echo $obj->medicine_category_status; ?></span>
                                                            <?php else: ?>
                                                                <span class="btn-xs badge-danger"><?php echo $obj->medicine_category_status; ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>views/medicine_category/edit.php?id=<?php echo base64_encode($obj->medicine_category_id); ?>" class="btn-xs btn-success"> <i class="fa fa-edit"></i></a>
                                                            <a href="javascript:void(0);" class="btn-xs badge-danger" data-toggle="modal" data-target="#delete_<?php echo $obj->medicine_category_id; ?>" ><i class="fa fa-trash-o"></i></a>
                                                            <div id="delete_<?php echo $obj->medicine_category_id; ?>" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-md">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <input type="hidden" name="medicine_category_id" value="<?php echo $obj->medicine_category_id ?>" />
                                                                                <h4 class="modal-title" style="color: #f8ac59;"><i class="fa fa-warning"></i> Warning</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Are you sure want to delete this medicine category? Click "Yes" to delete.</p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-primary" name="btn_delete_medicine_category"><i class="fa fa-check"></i> Yes</button>
                                                                                <button type="button" class="btn btn-primary btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
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
        <script src="<?php echo base_url('public/js/plugins/dataTables/datatables.min.js'); ?>"></script>
        
        <script type="text/javascript">
            $("#menu3").addClass("active");
            $("#menu3").parent().parent().addClass("treeview active");
            $("#menu3").parent().addClass("in");
        </script>
        <script>
            $(document).ready(function () {
                $('.dataTables-example').DataTable({
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [
                        {extend: 'copy'},
                        {extend: 'csv'},
                        {extend: 'excel', title: 'Category'},
                        {extend: 'pdf', title: 'Category'},
                        {extend: 'print',
                            customize: function (win) {
                                $(win.document.body).addClass('white-bg');
                                $(win.document.body).css('font-size', '10px');

                                $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                            }
                        }
                    ]
                });
            });
        </script>
    </body>
</html>
