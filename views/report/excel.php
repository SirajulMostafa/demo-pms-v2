<?php

include '../../config/config.php';

$start = '';
$end = '';
$output = '';
if (isset($_POST['export_excel'])) {

    $start = mysqli_real_escape_string($con, $_POST['start']);
    $end = mysqli_real_escape_string($con, $_POST['end']);
    $sql_report_date_wise = "SELECT order_info.*,order_details.*"
            . " FROM order_info"
            . " LEFT JOIN order_details ON"
            . " order_info.order_info_track_no = order_details.order_details_order_info_id"
            . " WHERE order_info_date BETWEEN '$start' AND '$end'";
    $result_report_date_wise = mysqli_query($con, $sql_report_date_wise);
    if ($result_report_date_wise) {
        $count = 1;
        $output .= '<table class="table" bordered="1"><tr><th style="border: 1px solid black">SL</th><th style="border: 1px solid black">Medicine Name</th><th style="border: 1px solid black">Sell Qty</th><th style="border: 1px solid black">Sell Price</th><th style="border: 1px solid black">Total</th><th style="border: 1px solid black">Sell Date</th></tr>';
        while ($obj = mysqli_fetch_object($result_report_date_wise)) {
            $output .= '<tr><td style="border: 1px solid black">' . $count . '</td><td style="border: 1px solid black">' . $obj->order_details_medicine_name . '</td><td style="border: 1px solid black">' . $obj->order_details_medicine_qty . '</td><td style="border: 1px solid black">' . $obj->order_details_medicine_sell_price . '</td><td style="border: 1px solid black">' . ($obj->order_details_medicine_sell_price * $obj->order_details_medicine_qty) . '</td><td style="border: 1px solid black">' . $obj->order_info_date . '</td></tr>';
            $count++;
        }
        $output .= '</table>';
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=report.xls");
        echo $output;
    }
}
?>