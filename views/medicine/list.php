<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$currency = '';
$store_currency = $store_info->store_currency;
if ($store_currency == 'Taka') {
    $currency = '৳';
} elseif ($store_currency == 'Dollar') {
    $currency = '$';
} elseif ($store_currency == 'Euro') {
    $currency = '€';
}
/*
 * Delete medicine
 */
$medicine_id = '';
if (isset($_POST['btn_delete_medicine'])) {
    extract($_POST);
    $medicine_id = validate_input($medicine_id);
    if ($medicine_id > 0 && $medicine_id != '') {
        $sql_delete_medicine = "DELETE FROM medicine WHERE medicine_id=$medicine_id";
        $result_delete_medicine = mysqli_query($con, $sql_delete_medicine);
        if ($result_delete_medicine) {
            $success = "Medicine deleted from the list successfully";
        } else {
            $error = "Something went wrong";
        }
    }
}

/*
 * load more quantity
 */
$more_quantity = '';
if (isset($_POST['btn_load_quantity'])) {
    extract($_POST);
    $medicine_id = validate_input($medicine_id);
    $more_quantity = validate_input($more_quantity);
    if ($medicine_id > 0 && $medicine_id != '' && $more_quantity > 0 && $more_quantity != '') {
        $sql_update_medicine_quantity = "UPDATE medicine SET medicine_quantity=medicine_quantity+$more_quantity WHERE medicine_id=$medicine_id";
        $result_update_medicine_quantity = mysqli_query($con, $sql_update_medicine_quantity);
        if ($result_update_medicine_quantity) {
            $success = "Medicine quantity loaded successfully";
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
                                    <h5>Medicine List</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>Medicine Name</th>
                                                    <th>Medicine Category</th>
                                                    <th>Buy Price</th>
                                                    <th>Sell Price</th>
                                                    <th>Quantity</th>
                                                    <th>Rack No</th>
                                                    <th>Company Name</th>
                                                    <th>Expire Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                /*
                                                 * Getting medicine data
                                                 */

                                                $sqlList = "SELECT medicine.*, medicine_category.* "
                                                        . "FROM medicine "
                                                        . "LEFT JOIN medicine_category ON medicine.medicine_category = medicine_category.medicine_category_id "
                                                        . "ORDER BY medicine_id DESC";
                                                $resultList = mysqli_query($con, $sqlList);
                                                ?>
                                                <?php while ($obj = mysqli_fetch_object($resultList)): ?>
                                                    <tr>
                                                        <td>
                                                            <?php if ($obj->medicine_expire_date < date('Y-m-d')): ?>
                                                                <b style="color: #ed5565;"><?php echo $obj->medicine_name; ?></b>
                                                                <br><small style="color: #ed5565;">expired</small>
                                                            <?php else: ?>
                                                                <?php echo $obj->medicine_name; ?>
                                                            <?php endif; ?>

                                                        </td>
                                                        <td><?php echo $obj->medicine_category_name; ?></td>
                                                        <td><?php echo $currency; ?>&nbsp;<?php echo $obj->medicine_buy_price; ?></td>
                                                        <td><?php echo $currency; ?>&nbsp;<?php echo $obj->medicine_sell_price; ?></td>
                                                        <td>
                                                            <?php echo $obj->medicine_quantity; ?>&nbsp;
                                                            <?php if ($obj->medicine_quantity <= 10): ?>
                                                                <a href="javascript:void(0);">
                                                                    <span data-toggle="modal" data-target="#load_<?php echo $obj->medicine_id; ?>" class="btn-primary btn-xs">Load</span>
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $obj->medicine_rack_no; ?></td>
                                                        <td><?php echo $obj->medicine_company; ?></td>
                                                        <td>
                                                            <?php if ($obj->medicine_expire_date < date('Y-m-d')): ?>
                                                                <b style="color: #ed5565;">
                                                                    <?php
                                                                    $date = date_create($obj->medicine_expire_date);
                                                                    echo date_format($date, "d-M-Y");
                                                                    ?> 
                                                                </b>
                                                            <?php else: ?>
                                                                <?php
                                                                $date = date_create($obj->medicine_expire_date);
                                                                echo date_format($date, "d-M-Y");
                                                                ?> 
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>views/medicine/edit.php?id=<?php echo base64_encode($obj->medicine_id); ?>" class="btn-xs btn-success"> <i class="fa fa-edit"></i></a>
                                                            <a href="javascript:void(0);" class="btn-xs badge-danger" data-toggle="modal" data-target="#delete_<?php echo $obj->medicine_id; ?>" ><i class="fa fa-trash-o"></i></a>
                                                            <div id="delete_<?php echo $obj->medicine_id; ?>" class="modal fade" role="dialog">
                                                                <div class="modal-dialog modal-md">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <input type="hidden" name="medicine_id" value="<?php echo $obj->medicine_id ?>" />
                                                                                <h4 class="modal-title" style="color: #f8ac59;"><i class="fa fa-warning"></i> Warning</h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Are you sure want to delete this medicine? Click "Yes" to delete.</p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-primary" name="btn_delete_medicine"><i class="fa fa-check"></i> Yes</button>
                                                                                <button type="button" class="btn btn-primary btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal fade" id="load_<?php echo $obj->medicine_id; ?>" role="dialog">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Add more quantity for <?php echo $obj->medicine_name; ?></h4>
                                                                        </div>
                                                                        <form method="POST" action="">
                                                                            <div class="modal-body">

                                                                                <input type="hidden" name="medicine_id" value="<?php echo $obj->medicine_id; ?>" />
                                                                                <div class="form-group">
                                                                                    <label>Quantity</label>
                                                                                    <input type="number" class="form-control" min="1" name="more_quantity" value="" required />
                                                                                </div>

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="submit" name="btn_load_quantity" class="btn btn-default">Submit</button>
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
                        {extend: 'excel', title: 'Product'},
                        {extend: 'pdf', title: 'Product'},
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
            $("#menu5").addClass("active");
            $("#menu5").parent().parent().addClass("treeview active");
            $("#menu5").parent().addClass("in");
        </script>
    </body>
</html>
