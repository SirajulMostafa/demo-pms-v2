<?php

include '../../config/config.php';
header("Content-type: application/json");
$session_id = session_id();
$return_array = array();
$count_error = 0;
$order_created_by = get_session("admin_id");
$order_info_status = 'Paid';
$temp_order_array = array();
extract($_POST);

$order_sub_total = validate_input($order_sub_total);
$order_discount = validate_input($order_discount);
$order_total = validate_input($order_total);
if ($order_sub_total != '' && $order_total != '') {
    // getting temp order data
    $sql_get_temp_order = "SELECT * FROM temp_order WHERE temp_order_session_id='$session_id'";
    $result_get_temp_order = mysqli_query($con, $sql_get_temp_order);
    if ($result_get_temp_order) {
        while ($obj_get_temp_order = mysqli_fetch_object($result_get_temp_order)) {
            $temp_order_array[] = $obj_get_temp_order;
        }
    }

    $order_info_track_no = "PMS" . random_code(6);
    // save order info data
    $variable = '';
    $variable .= 'order_info_session_id = "' . $session_id . '"';
    $variable .= ',order_info_track_no = "' . $order_info_track_no . '"';
    $variable .= ',order_info_subtotal = "' . $order_sub_total . '"';
    $variable .= ',order_info_discount = "' . $order_discount . '"';
    $variable .= ',order_info_total = "' . $order_total . '"';
    $variable .= ',order_info_date = "' . date('Y-m-d') . '"';
    $variable .= ',order_info_status = "' . $order_info_status . '"';
    $variable .= ',order_info_created_by = "' . $order_created_by . '"';

    $sql_insert_order_info = "INSERT INTO order_info SET $variable";

    $result_insert_order_info = mysqli_query($con, $sql_insert_order_info);
    if ($result_insert_order_info) {
        $last_insert_id = mysqli_insert_id($con);
        // save order details data
        foreach ($temp_order_array AS $order) {
            $details_variable = '';
            $details_variable .= 'order_details_order_info_id = "' . $order_info_track_no . '"';
            $details_variable .= ',order_details_medicine_id = "' . $order->temp_order_medicine_id . '"';
            $details_variable .= ',order_details_medicine_name = "' . $order->temp_order_medicine_name . '"';
            $details_variable .= ',order_details_medicine_qty = "' . $order->temp_order_qty . '"';
            $details_variable .= ',order_details_medicine_sell_price = "' . $order->temp_order_medicine_sell_price . '"';
            $details_variable .= ',order_details_medicine_buy_price = "' . $order->temp_order_medicine_buy_price . '"';
            $details_variable .= ',order_details_medicine_expire_date = "' . $order->temp_order_medicine_expire_date . '"';
            $details_variable .= ',order_details_medicine_profit = "' . $order->temp_order_profit . '"';
            $details_variable .= ',order_details_date = "' . date('Y-m-d') . '"';

            $sql_insert_order_details = "INSERT INTO order_details SET $details_variable";
            $result_insert_order_details = mysqli_query($con, $sql_insert_order_details);
            if ($result_insert_order_details) {
                // update stock quantity
                //Update stock table by product id
                $sql_update_stock = "UPDATE medicine SET medicine_quantity=medicine_quantity-$order->temp_order_qty "
                        . "WHERE medicine_id=$order->temp_order_medicine_id";
                $result_update_stock = mysqli_query($con, $sql_update_stock);
                if (!$result_update_stock) {
                    $count_error++;
                    $return_array = array("output" => "error", "msg" => "Stock updated error occured");
                }
            }
        }
    }
}
if ($count_error == 0) {
    // Truncate temp sales table, temp payment table, global discount table
    $sql_truncate = "DELETE FROM temp_order WHERE temp_order_session_id='$session_id'";
    $result_truncate = mysqli_query($con, $sql_truncate);
    if ($result_truncate) {
        $return_array = array("output" => "success",
            "msg" => "Sales completed successfully",
            "order_track_id" => $order_info_track_no
        );
    }
}
if (count($return_array) > 0) {
    echo json_encode($return_array);
}
?>