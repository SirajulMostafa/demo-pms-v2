<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
/*
 * Delete expense
 */
$expense_id = '';
if (isset($_POST['btn_delete_expense'])) {
    extract($_POST);
    $expense_id = validate_input($expense_id);
    if ($expense_id > 0 && $expense_id != '') {
        $sql_delete_expense = "DELETE FROM expense WHERE expense_id=$expense_id";
        $result_delete_expense = mysqli_query($con, $sql_delete_expense);
        if ($result_delete_expense) {
            $success = "Expense deleted successfully";
        } else {
            $error = "Something went wrong";
        }
    }
}
if (isset($_POST['btn_update_expense'])) {
    extract($_POST);
    $expense_id = validate_input($expense_id);
    $expense_criteria = validate_input($expense_criteria);
    $expense_amount = validate_input($expense_amount);
    if ($expense_criteria == '') {
        $error = "Expense criteria required";
    } elseif ($expense_amount == '') {
        $error = "Expense amount required";
    } else {
        $variable = '';
        $variable .= 'expense_criteria = "' . $expense_criteria . '"';
        $variable .= ',expense_amount = "' . $expense_amount . '"';
        $variable .= ',expense_updated_by = "' . get_session('admin_id') . '"';

        $sql_update_expense = "UPDATE expense SET $variable WHERE expense_id=$expense_id";
        $result_update_expense = mysqli_query($con, $sql_update_expense);
        if ($result_update_expense) {
            $success = "Expense updated successfully";
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
                                    <h5>All Expense List</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>Expense Criteria</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                /*
                                                 * Getting expense data
                                                 */

                                                $sqlList = "SELECT * FROM expense "
                                                        . "ORDER BY expense_id DESC";
                                                $resultList = mysqli_query($con, $sqlList);
                                                ?>
                                                <?php while ($obj = mysqli_fetch_object($resultList)): ?>
                                                    <tr>
                                                        <td><?php echo $obj->expense_criteria; ?></td>
                                                        <td><?php echo $obj->expense_amount; ?></td>
                                                        <td>
                                                            <?php
                                                            $date = date_create($obj->expense_date);
                                                            echo date_format($date, "d-M-Y");
                                                            ?> 
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#expense_<?php echo $obj->expense_id; ?>" class="btn-xs btn-success"> <i class="fa fa-edit"></i></a>
                                                            <a href="javascript:void(0);" class="btn-xs badge-danger" data-toggle="modal" data-target="#delete_<?php echo $obj->expense_id; ?>" ><i class="fa fa-trash-o"></i></a>
                                                            <div id="delete_<?php echo $obj->expense_id; ?>" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <input type="hidden" name="expense_id" value="<?php echo $obj->expense_id ?>" />
                                                                                <h4 class="modal-title" style="color: red;"><i class="fa fa-warning"></i> Are you sure?</h4>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-danger" name="btn_delete_expense">Delete</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="expense_<?php echo $obj->expense_id; ?>" class="modal fade" role="dialog">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Expense</h4>
                                                                            </div>
                                                                            <div class="modal-body">

                                                                                <input type="hidden" name="expense_id" value="<?php echo $obj->expense_id ?>" />
                                                                                <div class="form-group">
                                                                                    <label>Expense Criteria</label>
                                                                                    <input type="text" class="form-control" name="expense_criteria" value="<?php echo $obj->expense_criteria; ?>" required />
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label>Amount</label>
                                                                                    <input type="text" class="form-control" name="expense_amount" value="<?php echo $obj->expense_amount; ?>" required />
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-primary" name="btn_update_expense"> <i class="fa fa-check"></i> Submit</button>
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
        <script>
            $(document).ready(function () {
                $('.dataTables-example').DataTable({
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [
                        {extend: 'copy'},
                        {extend: 'csv'},
                        {extend: 'excel', title: 'Expense'},
                        {extend: 'pdf', title: 'Expense'},
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
            $("#menu8").addClass("active");
            $("#menu8").parent().parent().addClass("treeview active");
            $("#menu8").parent().addClass("in");
        </script>
    </body>
</html>
