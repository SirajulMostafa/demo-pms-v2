<?php
include '../../config/config.php';
$start = '';
$end = '';
$gross_amount = 0;
$gross_profit = 0;
$gross_expense = 0;
$store_info = get_store_info();
$store_currency = $store_info->store_currency;
if ($store_currency == 'Taka') {
    $currency = '৳';
} elseif ($store_currency == 'Dollar') {
    $currency = '$';
} elseif ($store_currency == 'Euro') {
    $currency = '€';
}
if (isset($_GET['start'])) {
    $start = $_GET['start'];
}
if (isset($_GET['end'])) {
    $end = $_GET['end'];
}
if ($start != '' && $end != '') {
    $sql_report_date_wise = "SELECT order_info.*,order_details.*"
            . " FROM order_info"
            . " LEFT JOIN order_details ON"
            . " order_info.order_info_track_no = order_details.order_details_order_info_id"
            . " WHERE order_info_date BETWEEN '$start' AND '$end'";
    $result_report_date_wise = mysqli_query($con, $sql_report_date_wise);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Print Report</title>
        <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet">
    </head>
    <body onload="window.print()">
        <div class="container">
            <h1 style="text-align: center">
                Sales Report of 
                <?php
                $date_start = date_create($start);
                echo date_format($date_start, "d-M-Y");
                ?> 
                to
                <?php
                $date_end = date_create($end);
                echo date_format($date_end, "d-M-Y");
                ?> 
            </h1>
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Medicine Name</th>
                        <th>Sell Qty</th>
                        <th>Sell Price</th>
                        <th>Total</th>
                        <th>Sell Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php while ($obj_report = mysqli_fetch_object($result_report_date_wise)): ?>
                        <tr>
                            <td><?php echo $count; ?></td>
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
                    <?php endwhile; ?>
                    <tr>
                        <?php
                        $gross_amount = get_gross_sale($start, $end);
                        if ($gross_amount == '') {
                            $gross_amount = 0;
                        }
                        ?>
                        <td colspan="2" style="font-size: large">Gross Sales = <?php echo $gross_amount; ?></td>
                        <?php
                        $gross_profit = get_gross_profit($start, $end);
                        if ($gross_profit == '') {
                            $gross_profit = 0;
                        }
                        ?>
                        <td colspan = "2" style="font-size: large">Total Sales Profit = <?php echo $gross_profit; ?></td>
                        <?php
                        $gross_expense = get_gross_expense($start, $end);
                        if ($gross_expense == '') {
                            $gross_expense = 0;
                        }
                        ?>
                        <td colspan = "2" style="font-size: large">Gross Expense = <?php echo $gross_expense; ?></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </body>
</html>