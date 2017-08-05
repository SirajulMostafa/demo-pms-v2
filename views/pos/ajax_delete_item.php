<?php

include '../../config/config.php';
header("Content-type: application/json");
$session_id = session_id();
$return_array = array();
$count_error = 0;
$item_id = '';
extract($_POST);

if ($item_id > 0 && $item_id != '') {
    $sql_delete_item = "DELETE FROM temp_order WHERE temp_order_id=$item_id AND temp_order_session_id='$session_id'";
    $result_delete_item = mysqli_query($con, $sql_delete_item);
    if (!$result_delete_item) {
        $count_error++;
        $return_array = array("output" => "error", "msg" => "Item not deleted. Please try again.");
    } else {
        $sql_get_data = "SELECT * FROM temp_order WHERE temp_order_session_id='$session_id'";
        $result_get_data = mysqli_query($con, $sql_get_data);
        $count_get_data = mysqli_num_rows($result_get_data);
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
            "msg" => "Deleted successfully",
            "temp_sales_sub_total" => $temp_sales_sub_total,
            "item_count" => $count_get_data
        );
    }

    if (count($return_array) > 0) {
        echo json_encode($return_array);
    }
}
?>