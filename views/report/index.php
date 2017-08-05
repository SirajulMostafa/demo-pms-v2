<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$store_currency = $store_info->store_currency;
if ($store_currency == 'Taka') {
    $currency = '৳';
} elseif ($store_currency == 'Dollar') {
    $currency = '$';
} elseif ($store_currency == 'Euro') {
    $currency = '€';
}
$start_date = '';
$end_date = '';
$count_report_data = 0;
$flag = 0;
if (isset($_POST['btn_report'])) {
    extract($_POST);
    $start_date = validate_input($start_date);
    $end_date = validate_input($end_date);
    if ($start_date && $end_date) {
        $sql_report_date_wise = "SELECT order_info.*,order_details.*"
                . " FROM order_info"
                . " LEFT JOIN order_details ON"
                . " order_info.order_info_track_no = order_details.order_details_order_info_id"
                . " WHERE order_info_date BETWEEN '$start_date' AND '$end_date'";
        $result_report_date_wise = mysqli_query($con, $sql_report_date_wise);
        $count_report_data = mysqli_num_rows($result_report_date_wise);
        $flag = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
        <link href="<?php echo base_url('public/css/plugins/datapicker/datepicker3.css'); ?>" rel="stylesheet">
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
                                    <h5>Sales Report</h5>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <form method="POST" action="">
                                                <div class="form-group" id="data_5">
                                                    <label class="font-noraml">Choose Start & End Date</label>
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" class="input-sm form-control" name="start_date" value="<?php echo $start_date; ?>" placeholder="Choose start date" required/>
                                                        <span class="input-group-addon">to</span>
                                                        <input type="text" class="input-sm form-control" name="end_date" value="<?php echo $end_date; ?>" placeholder="Choose end date" required/>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" type="submit" name="btn_report"><i class="fa fa-file-text" aria-hidden="true"></i>
                                                    &nbsp; Get Report</button>
                                            </form>      
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="widget style1 lazur-bg">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <i class="fa fa-bar-chart fa-4x"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-right">
                                                        <span> Gross Sales </span>
                                                        <?php
                                                        $gross_amount = get_gross_sale($start_date, $end_date);
                                                        if ($gross_amount == '') {
                                                            $gross_amount = 0;
                                                        }
                                                        ?>
                                                        <h2 class="font-bold" style="font-size: 22px;"><?php echo $currency; ?>&nbsp;<?php echo $gross_amount; ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="widget style1 navy-bg">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <i class="fa fa-credit-card fa-4x"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-right">
                                                        <span> Total Sales Profit </span>
                                                        <?php
                                                        $gross_profit = get_gross_profit($start_date, $end_date);
                                                        if ($gross_profit == '') {
                                                            $gross_profit = 0;
                                                        }
                                                        ?>
                                                        <h2 class="font-bold" style="font-size: 22px;"><?php echo $currency; ?>&nbsp;<?php echo $gross_profit; ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="widget style1 yellow-bg">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <i class="fa fa-line-chart fa-4x"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-right">
                                                        <span> Gross Expense </span>
                                                        <?php
                                                        $gross_expense = get_gross_expense($start_date, $end_date);
                                                        if ($gross_expense == '') {
                                                            $gross_expense = 0;
                                                        }
                                                        ?>
                                                        <h2 class="font-bold" style="font-size: 22px;"><?php echo $currency; ?>&nbsp;<?php echo $gross_expense; ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="widget style1 red-bg">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <i class="fa fa-money fa-4x"></i>
                                                    </div>
                                                    <div class="col-xs-9 text-right">
                                                        <span> Gross Profit </span>
                                                        <?php
                                                        $gross_profit = get_gross_profit($start_date, $end_date);
                                                        if ($gross_profit == '') {
                                                            $gross_profit = 0;
                                                        }
                                                        $gross_expense = get_gross_expense($start_date, $end_date);
                                                        if ($gross_expense == '') {
                                                            $gross_expense = 0;
                                                        }
                                                        $final_profit = ($gross_profit - $gross_expense);
                                                        ?>
                                                        <h2 class="font-bold" style="font-size: 22px;"><?php echo $currency; ?>&nbsp;<?php echo $final_profit; ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($flag == 1): ?>
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>Show Reports of 
                                            <b style="color: #18A689;">
                                                <?php
                                                $date = date_create($start_date);
                                                echo date_format($date, "d-M-Y");
                                                ?> 
                                            </b>
                                            to 
                                            <b style="color: #18A689;">
                                                <?php
                                                $date = date_create($end_date);
                                                echo date_format($date, "d-M-Y");
                                                ?>
                                            </b>
                                        </h5>
                                    </div>

                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                                <thead>
                                                    <tr>
                                                        <th>Serial No</th>
                                                        <th>Medicine Name</th>
                                                        <th data-hide="phone">Sell Quantity</th>
                                                        <th data-hide="phone">Sell Price</th>
                                                        <th data-hide="phone">Total Amount</th>
                                                        <th data-hide="phone">Sell Date</th>
                                                    </tr>
                                                </thead>
                                                <?php if ($count_report_data > 0): ?>
                                                    <?php $sl = 1; ?>
                                                    <tbody>
                                                        <?php while ($obj_report = mysqli_fetch_object($result_report_date_wise)): ?>
                                                            <tr>
                                                                <td><?php echo $sl; ?></td>
                                                                <td><?php echo $obj_report->order_details_medicine_name; ?></td>
                                                                <td><?php echo $obj_report->order_details_medicine_qty; ?></td>
                                                                <td><?php echo $obj_report->order_details_medicine_sell_price; ?>&nbsp;<?php echo $currency; ?></td>
                                                                <td><?php echo ($obj_report->order_details_medicine_sell_price * $obj_report->order_details_medicine_qty); ?>&nbsp;<?php echo $currency; ?></td>
                                                                <td>
                                                                    <?php
                                                                    $date = date_create($obj_report->order_info_date);
                                                                    echo date_format($date, "d-M-Y");
                                                                    ?> 
                                                                </td>
                                                            </tr>
                                                            <?php $sl++; ?>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                <?php else: ?>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;" colspan="5">No data found</td>
                                                        </tr>
                                                    </tbody>
                                                <?php endif; ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
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
        <script src="<?php echo base_url('public/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
        <script>
            $(document).ready(function () {
                $('.dataTables-example').DataTable({
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [
                        {extend: 'copy'},
                        {extend: 'csv'},
                        {extend: 'excel', title: 'Report'},
                        {extend: 'pdf', title: 'Report'},
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

        <script>
            $(document).ready(function () {

                $('#data_5 .input-daterange').datepicker({
                    format: 'yyyy-mm-dd',
                    keyboardNavigation: false,
                    forceParse: false,
                    autoclose: true
                });
            });
        </script>
    </script>
    <script type="text/javascript">
        $("#menu11").addClass("active");
        $("#menu11").parent().parent().addClass("treeview active");
        $("#menu11").parent().addClass("in");
    </script>
</body>
</html>
