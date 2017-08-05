<?php

include '../../config/config.php';
header("Content-type: application/json");
$session_id = session_id();
$return_array = array();
$count_error = 0;
$order_id = 0;
$item_id = 0;
$sell_price = 0;
$buy_price = 0;
$item_qty = 0;
$previous_quantity = '';

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
extract($_POST);

if ($item_qty != '' && $item_qty > 0) {
    // checking quantity available or not
    $sql_check_quantity = "SELECT * FROM medicine WHERE medicine_id=$item_id LIMIT 1";
    $result_check_quantity = mysqli_query($con, $sql_check_quantity);
    $count_check_quantity = mysqli_num_rows($result_check_quantity);
    if ($count_check_quantity > 0) {
        $obj_check_quantity = mysqli_fetch_object($result_check_quantity);
        $available_quantity = $obj_check_quantity->medicine_quantity;



        if ($available_quantity >= $item_qty) {
            // update the item cart table

            $profit = ($sell_price - $buy_price);
            $cal_profit = $profit * $item_qty;

            $variable = '';
            $variable .= 'temp_order_qty = "' . $item_qty . '"';
            $variable .= ',temp_order_total = "' . ($sell_price * $item_qty) . '"';
            $variable .= ',temp_order_profit = "' . $cal_profit . '"';

            $sql_update_quantity = "UPDATE temp_order SET $variable WHERE temp_order_session_id='$session_id' AND temp_order_id=$order_id";
            $result_update_quantity = mysqli_query($con, $sql_update_quantity);
            if (!$result_update_quantity) {
                $count_error++;
                $return_array = array("output" => "error", "msg" => "Quantity not updated. Please try again.");
            }
        } else {
            // getting previous quantity
            $sql_get_quantity = "SELECT temp_order_id,temp_order_qty,temp_order_session_id FROM temp_order WHERE temp_order_id='$order_id' AND temp_order_session_id='$session_id'";
            $result_get_quantity = mysqli_query($con, $sql_get_quantity);
            if ($result_get_quantity) {
                $obj_get_quantity = mysqli_fetch_object($result_get_quantity);
                $previous_quantity = $obj_get_quantity->temp_order_qty;
            }
            // error for quantity not available
            $count_error++;
            $return_array = array(
                "output" => "error",
                "msg" => "Quantity not available in record",
                "previous_qty" => $previous_quantity
            );
        }
    } else {
        //error for not finding any data
        $count_error++;
        $return_array = array("output" => "error", "msg" => "No data found in record. Please check medicine storage");
    }
} else {
    $count_error++;
    $return_array = array("output" => "error", "msg" => "Quantity required.");
}

// getting temp cart sub total amount
$sql_temp_sub_total = "SELECT SUM(temp_order_total) AS temp_sales_sub_total FROM temp_order"
        . " WHERE temp_order_session_id='$session_id'";
$result_temp_sub_total = mysqli_query($con, $sql_temp_sub_total);
if ($result_temp_sub_total) {
    $obj_temp_sub_total = mysqli_fetch_object($result_temp_sub_total);
    $temp_sales_sub_total = $obj_temp_sub_total->temp_sales_sub_total;
}

// if no error
if ($count_error == 0) {
    $return_array = array("output" => "success",
        "msg" => "Quantity updated successfully",
        "temp_sales_quantity" => $item_qty,
        "temp_sales_sub_total" => $temp_sales_sub_total,
        "temp_sales_sell_price" => $sell_price,
        "currency" => $currency
    );
}

if (count($return_array) > 0) {
    echo json_encode($return_array);
}
?>