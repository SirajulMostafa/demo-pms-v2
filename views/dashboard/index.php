<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$total_medicine = get_medicine_report();
$today_expense = get_today_expense();
$total_expired = get_total_expired();
$today_sale = get_today_sales();
if ($today_sale == '') {
    $today_sale = 0;
}
if ($today_expense == '') {
    $today_expense = 0;
}
$currency = '';
$store_currency = $store_info->store_currency;
if ($store_currency == 'Taka') {
    $currency = '৳';
} elseif ($store_currency == 'Dollar') {
    $currency = '$';
} elseif ($store_currency == 'Euro') {
    $currency = '€';
}
$no_of_sales = 0;
$total_sales_amount = 0;
$no_of_expense = 0;
$total_expense = 0;
$profit_amount = 0;
$total_profit = 0;
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
    </head>
    <body>
        <div id="wrapper">
            <?php include base_path('left_menu.php'); ?>
            <div id="page-wrapper" class="gray-bg">
                <?php include base_path('top_bar.php'); ?>
                <div class="wrapper wrapper-content">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-success pull-right">Today</span>
                                    <h5>Sales</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?php echo $currency; ?>&nbsp;<?php echo $today_sale; ?></h1>
                                    <small>Total Sales Today</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-info pull-right">Today</span>
                                    <h5>Expenses</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?php echo $currency; ?>&nbsp;<?php echo $today_expense; ?></h1>
                                    <small>Total Expenses Today</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-warning pull-right">Total</span>
                                    <h5>Medicines</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?php echo $total_medicine; ?></h1>
                                    <small>Total Medicine In Store</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-danger pull-right">Expired</span>
                                    <h5>Medicines</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?php echo $total_expired; ?></h1>
                                    <small>Medicines Expired In Store</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Sales & Profit</h5>
                                </div>
                                <div class="ibox-content">
                                    <canvas id="myChart" style="width: 100%;height: 200px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Statistics This Month</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php
                                // Total Sales
                                $sql_current_month_sales = "SELECT COUNT(*) as no_of_sales,"
                                        . " SUM(order_info_total) as total_sales_amount FROM order_info"
                                        . " WHERE MONTH(order_info_created_on) = MONTH(CURRENT_DATE())";
                                $result_current_month_sales = mysqli_query($con, $sql_current_month_sales);
                                if ($result_current_month_sales) {
                                    $obj_current_month_sales = mysqli_fetch_object($result_current_month_sales);
                                    $no_of_sales = $obj_current_month_sales->no_of_sales;
                                    $total_sales_amount = $obj_current_month_sales->total_sales_amount;
                                }
                                if ($total_sales_amount == '') {
                                    $total_sales_amount = 0;
                                }
                                // Profit
                                $sql_current_month_profit = "SELECT SUM(order_details_medicine_profit) as total_profit"
                                        . " FROM order_details"
                                        . " WHERE MONTH(order_details_date) = MONTH(CURRENT_DATE())";
                                $result_current_month_profit = mysqli_query($con, $sql_current_month_profit);
                                if ($result_current_month_profit) {
                                    $obj_current_month_profit = mysqli_fetch_object($result_current_month_profit);
                                    $total_profit = $obj_current_month_profit->total_profit;
                                }
                                if ($total_profit == '') {
                                    $total_profit = 0;
                                }

                                $sql_current_month_expense = "SELECT COUNT(*) as no_of_expense,"
                                        . " SUM(expense_amount) as total_expense_amount FROM expense"
                                        . " WHERE MONTH(expense_created_on) = MONTH(CURRENT_DATE())";
                                $result_current_month_expense = mysqli_query($con, $sql_current_month_expense);
                                if ($result_current_month_expense) {
                                    $obj_current_month_expense = mysqli_fetch_object($result_current_month_expense);
                                    $no_of_expense = $obj_current_month_expense->no_of_expense;
                                    $total_expense = $obj_current_month_expense->total_expense_amount;
                                }
                                ?>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table table-hover margin bottom">
                                                <tbody>
                                                    <tr>
                                                        <th>Number Of Sales</th>
                                                        <td><?php echo $no_of_sales; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Sales Amount</th>
                                                        <td><?php echo $currency; ?>&nbsp;<?php echo $total_sales_amount; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><b style="color: red">Sales Profit</b></th>
                                                        <td>
                                                            <span class="label label-primary" style="font-size: 13px;">
                                                                <?php echo $currency; ?>&nbsp;<?php echo $total_profit; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Number Of Expenses</th>
                                                        <td><?php echo $no_of_expense; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Expenses Amount</th>
                                                        <td>
                                                            <span class="label label-primary" style="font-size: 13px;">
                                                                <?php echo $currency; ?>&nbsp;<?php echo $total_expense; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Latest Sales</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <a class="close-link">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="table table-hover margin bottom">
                                                <thead>
                                                    <tr>
                                                        <th>Order No</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql_recent_sale = "SELECT * FROM order_info ORDER BY order_info_created_on DESC LIMIT 4";
                                                    $result_recent_sale = mysqli_query($con, $sql_recent_sale);
                                                    $count_recent_sale = mysqli_num_rows($result_recent_sale);
                                                    if ($count_recent_sale > 0):
                                                        while ($obj_recent = mysqli_fetch_object($result_recent_sale)):
                                                            ?>
                                                            <tr>
                                                                <td><b style="color: #18A689;"><?php echo $obj_recent->order_info_track_no; ?></b></td>
                                                                <td class="text-center">
                                                                    <?php
                                                                    $date = date_create($obj_recent->order_info_date);
                                                                    echo date_format($date, "d-M-Y");
                                                                    ?> 
                                                                </td>
                                                                <td class="text-center"><?php echo $currency; ?>&nbsp;<?php echo $obj_recent->order_info_total; ?></td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
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
        <script src="<?php echo base_url('public/js/chart.js'); ?>"></script>
        <script type="text/javascript">
            $("#menu1").addClass("active");
            $("#menu1").parent().parent().addClass("treeview active");
            $("#menu1").parent().addClass("in");
        </script>
        <script>
            $(document).ready(function () {


                var data = {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [
                        {
                            fillColor: "rgba(220,220,220,0.5)",
                            strokeColor: "rgba(220,220,220,1)",
                            pointColor: "rgba(220,220,220,1)",
                            pointStrokeColor: "#fff",
                            data: [65, 59, 90, 81, 56, 55, 40, 20, 58, 56, 94, 58]
                        },
                        {
                            fillColor: "rgba(151,187,205,0.5)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            data: [28, 48, 40, 19, 96, 27, 100, 85, 65, 48, 94, 51]
                        }
                    ]
                }

                var options = {
                    animation: true
                };

                var ctx = document.getElementById("myChart").getContext("2d");
                new Chart(ctx).Bar(data, options);
            });
        </script>
    </body>
</html>
