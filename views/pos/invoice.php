<?php
include '../../config/config.php';
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
$order_track_no = '';
if (isset($_GET['id'])) {
    $order_track_no = $_GET['id'];
}

$sql_get_order_info = "SELECT * FROM order_info WHERE order_info_track_no='$order_track_no'";
$result_get_order_info = mysqli_query($con, $sql_get_order_info);
if ($result_get_order_info) {
    $obj_get_order_info = mysqli_fetch_object($result_get_order_info);
    $sub_total = $obj_get_order_info->order_info_subtotal;
    $discount = $obj_get_order_info->order_info_discount;
    $total = $obj_get_order_info->order_info_total;
    $date = $obj_get_order_info->order_info_date;
    $status = $obj_get_order_info->order_info_status;
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
        <style>
            .invoice-title h2, .invoice-title h3 {
                display: inline-block;
            }

            .table > tbody > tr > .no-line {
                border-top: none;
            }

            .table > thead > tr > .no-line {
                border-bottom: none;
            }

            .table > tbody > tr > .thick-line {
                border-top: 2px solid;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="invoice-title">
                        <h3><?php echo $store_info->store_name; ?></h3><h3 class="pull-right">Track No # <?php echo $order_track_no; ?></h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <address>
                                <strong>Payment Status:</strong><br>
                                <?php echo $status; ?>
                            </address>
                        </div>
                        <div class="col-xs-6 text-right">
                            <address>
                                <strong>Order Date:</strong><br>
                                <?php echo $date; ?><br><br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Order summary</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <td><strong>Item</strong></td>
                                            <td class="text-center"><strong>Price</strong></td>
                                            <td class="text-center"><strong>Quantity</strong></td>
                                            <td class="text-right"><strong>Totals</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql_get_order_data = "SELECT * FROM order_details WHERE order_details_order_info_id='$order_track_no'";
                                        $result_get_order_data = mysqli_query($con, $sql_get_order_data);
                                        ?>
                                        <?php while ($obj_get_order_data = mysqli_fetch_object($result_get_order_data)): ?>
                                            <tr>
                                                <td><?php echo $obj_get_order_data->order_details_medicine_name; ?></td>
                                                <td class="text-center"><?php echo $currency; ?>&nbsp;<?php echo $obj_get_order_data->order_details_medicine_sell_price; ?></td>
                                                <td class="text-center"><?php echo $obj_get_order_data->order_details_medicine_qty; ?></td>
                                                <td class="text-right"><?php echo $currency; ?>&nbsp;<?php echo number_format(($obj_get_order_data->order_details_medicine_sell_price * $obj_get_order_data->order_details_medicine_qty), 2, '.', ''); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                        <tr>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                            <td class="thick-line text-right"><?php echo $currency; ?>&nbsp;<?php echo $sub_total; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Discount</strong></td>
                                            <td class="no-line text-right"><?php echo $currency; ?>&nbsp;<?php echo $discount; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="no-line text-center"><strong>Total</strong></td>
                                            <td class="no-line text-right"><?php echo $currency; ?>&nbsp;<?php echo $total; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url('public/js/jquery.js'); ?>"></script>
        <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript">
            window.onload = function () {
                window.print();
                window.setTimeout(base_url + "views/pos/index.php", 100);
            };
        </script>
    </body>
</html>
