<?php

include '../../config/config.php';
$input = trim($_REQUEST['term']);
$display_json = array();
$json_array = array();

$input = preg_replace('/\s+/', ' ', $input);

$sql_search = 'SELECT medicine_id,medicine_name FROM medicine WHERE medicine_status="Active" AND medicine_name LIKE "%' . $input . '%"';

$result_search = mysqli_query($con, $sql_search);
if (mysqli_num_rows($result_search) > 0) {
    while ($obj_search = mysqli_fetch_assoc($result_search)) {
        $json_array["id"] = $obj_search['medicine_id'];
        $json_array["value"] = $obj_search['medicine_name'];
        $json_array["label"] = $obj_search['medicine_name'];
        array_push($display_json, $json_array);
    }
} else {
    $json_array["id"] = "";
    $json_array["value"] = "";
    $json_array["label"] = "No Result Found !";
    array_push($display_json, $json_array);
}


$json_write = json_encode($display_json); //encode that search data
print $json_write;
?>
