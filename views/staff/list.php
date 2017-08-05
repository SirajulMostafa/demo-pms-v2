<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
/*
 * Delete staff
 */
$admin_id = '';
if (isset($_POST['btn_delete_staff'])) {
    extract($_POST);
    $admin_id = validate_input($admin_id);
    if ($admin_id > 0 && $admin_id != '') {
        $sql_delete = "DELETE FROM admin WHERE admin_id=$admin_id";
        $result_delete = mysqli_query($con, $sql_delete);
        if ($result_delete) {
            $success = "Staff deleted successfully";
        } else {
            $error = "Something went wrong";
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
                                    <h5>All Staff List</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>Staff Name</th>
                                                    <th>Email</th>
                                                    <th>Username</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>Type</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                /*
                                                 * Getting expense data
                                                 */

                                                $sqlList = "SELECT * FROM admin "
                                                        . "ORDER BY admin_id DESC";
                                                $resultList = mysqli_query($con, $sqlList);
                                                ?>
                                                <?php while ($obj = mysqli_fetch_object($resultList)): ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $obj->admin_name; ?>
                                                            <?php if (get_session('admin_id') == $obj->admin_id): ?>
                                                                <br><small style="color:#1ab394">[Currently logged in]</small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $obj->admin_email; ?></td>
                                                        <td><?php echo $obj->admin_username; ?></td>
                                                        <td><?php echo $obj->admin_phone; ?></td>
                                                        <td><?php echo $obj->admin_address; ?></td>
                                                        <td><?php echo $obj->admin_type; ?></td>
                                                        <td><?php echo $obj->admin_status; ?></td>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>views/staff/edit.php?id=<?php echo base64_encode($obj->admin_id); ?>" class="btn-xs btn-success"> <i class="fa fa-edit"></i></a>
                                                            <?php if (get_session('admin_id') != $obj->admin_id): ?>
                                                                <a href="javascript:void(0);" class="btn-xs badge-danger" data-toggle="modal" data-target="#delete_<?php echo $obj->admin_id; ?>" ><i class="fa fa-trash-o"></i></a>
                                                                <div id="delete_<?php echo $obj->admin_id; ?>" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog modal-sm">
                                                                        <div class="modal-content">
                                                                            <form method="POST" action="">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                    <input type="hidden" name="admin_id" value="<?php echo $obj->admin_id ?>" />
                                                                                    <h4 class="modal-title" style="color: red;"><i class="fa fa-warning"></i> Are you sure?</h4>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="submit" class="btn btn-danger" name="btn_delete_staff">Delete</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
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
        <script>
            $(document).ready(function () {
                $('.dataTables-example').DataTable({
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [
                        {extend: 'copy'},
                        {extend: 'csv'},
                        {extend: 'excel', title: 'Staff'},
                        {extend: 'pdf', title: 'Staff'},
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
        <script type="text/javascript">
            $("#menu10").addClass("active");
            $("#menu10").parent().parent().addClass("treeview active");
            $("#menu10").parent().addClass("in");
        </script>
    </body>
</html>
