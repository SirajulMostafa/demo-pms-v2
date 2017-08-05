<?php

include '../../config/config.php';
header("Content-type: application/json");
$session_id = session_id();
$return_array = array();
$count_error = 0;

$store_info = get_store_info();
$currency = '';
$store_currency = $store_info->store_currency;
$store_discount_type = $store_info->store_discount_type;
if ($store_currency == 'Taka') {
    $currency = '৳';
} elseif ($store_currency == 'Dollar') {
    $currency = '$';
} elseif ($store_currency == 'Euro') {
    $currency = '€';
}

if ($store_discount_type == "Flat") {
    $discount_type = "0"; // flat rate
} else {
    $discount_type = "1"; // percent
}
extract($_POST);
$medicine_name_type = validate_input(trim($medicine_name_type));

if ($medicine_name_type != '' && !empty($medicine_name_type)) {
    $sql_temp_cart = "SELECT * FROM medicine WHERE medicine_name='$medicine_name_type' LIMIT 1";
    $result_temp_cart = mysqli_query($con, $sql_temp_cart);
    if ($result_temp_cart) {
        $obj_temp_cart = mysqli_fetch_object($result_temp_cart);
		// checking stock
        if ($obj_temp_cart->medicine_quantity > 1) {
            // Checking if temp order is empty or not
            $sql_temp_order = "SELECT * FROM temp_order WHERE temp_order_session_id='$session_id' AND temp_order_medicine_name='$medicine_name_type'";
            $result_temp_order = mysqli_query($con, $sql_temp_order);
            $count_temp_order = mysqli_num_rows($result_temp_order);

            if ($count_temp_order > 0) {
                $obj_temp_order = mysqli_fetch_object($result_temp_order);

                // update the row
                $profit = (($obj_temp_order->temp_order_medicine_sell_price - $obj_temp_cart->medicine_buy_price) * ($obj_temp_order->temp_order_qty + 1));

                $variable = '';
                $variable .= 'temp_order_qty = "' . ($obj_temp_order->temp_order_qty + 1) . '"';
                $variable .= ',temp_order_total = "' . ($obj_temp_order->temp_order_medicine_sell_price * ($obj_temp_order->temp_order_qty + 1)) . '"';
                $variable .= ',temp_order_profit = "' . $profit . '"';

                $sql_update_temp_order = "UPDATE temp_order SET $variable WHERE temp_order_id='$obj_temp_order->temp_order_id'";
                $result_update_temp_order = mysqli_query($con, $sql_update_temp_order);
                if (!$result_update_temp_order) {
                    $count_error++;
                    $return_array = array("output" => "error", "msg" => "Order failed to update");
                }
            } else {
                // insert the order

                $variable = '';
                $variable .= 'temp_order_medicine_id = "' . $obj_temp_cart->medicine_id . '"';
                $variable .= ',temp_order_medicine_name = "' . $obj_temp_cart->medicine_name . '"';
                $variable .= ',temp_order_qty = "' . 1 . '"';
                $variable .= ',temp_order_medicine_buy_price = "' . $obj_temp_cart->medicine_buy_price . '"';
                $variable .= ',temp_order_medicine_sell_price = "' . $obj_temp_cart->medicine_sell_price . '"';
                $variable .= ',temp_order_medicine_expire_date = "' . $obj_temp_cart->medicine_expire_date . '"';
                $variable .= ',temp_order_total = "' . ($obj_temp_cart->medicine_sell_price * 1) . '"';
                $variable .= ',temp_order_session_id = "' . $session_id . '"';
                $variable .= ',temp_order_profit = "' . ($obj_temp_cart->medicine_sell_price - $obj_temp_cart->medicine_buy_price) . '"';
				
			
                $sql_insert_temp_order = "INSERT INTO temp_order SET $variable";
                $result_insert_temp_order = mysqli_query($con, $sql_insert_temp_order);
                if (!$result_insert_temp_order) {
                    $count_error++;
                    $return_array = array("output" => "error", "msg" => "Order failed to add");
                }
            }
        } else {
            // error = insufficient medicine
            $count_error++;
            $return_array = array("output" => "error", "msg" => "Insufficient quantity available in record");
        }
    } else {
        // no data found
        $count_error++;
        $return_array = array("output" => "error", "msg" => "No data found in record");
    }
} else {
    // error = medicine name required
    $count_error++;
    $return_array = array("output" => "error", "msg" => "Medicine name required");
}


// generating temp cart
$temp_sales_cart = array();
$sql_temp_sales_cart = "SELECT temp_order.*,medicine.medicine_id,medicine.medicine_quantity"
        . " FROM temp_order"
        . " LEFT JOIN medicine ON temp_order.temp_order_medicine_id = medicine.medicine_id"
        . " WHERE temp_order_session_id='$session_id'";
$result_temp_sales_cart = mysqli_query($con, $sql_temp_sales_cart);
if ($result_temp_sales_cart) {
    while ($obj_temp_sales_cart = mysqli_fetch_object($result_temp_sales_cart)) {
        $temp_sales_cart[] = $obj_temp_sales_cart;
    }
} else {
    $count_error++;
    $return_array = array("output" => "error", "msg" => "Temp order data get failed");
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
        "msg" => "Order placed successfully",
        "temp_sales_cart" => $temp_sales_cart,
        "temp_sales_sub_total" => $temp_sales_sub_total,
        "currency" => $currency,
        "discount_type" => $discount_type,
        "store_currency" => $store_currency
    );
}

// return record
if (count($return_array) > 0) {
    echo json_encode($return_array);
}
?>