<?php
include '../../config/config.php';
check_admin_login();
$store_info = get_store_info();
$session_id = session_id();
$page_cart_flag = 0;
$currency = '';
$discount_type = '';
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
    $discount_type = "Flat Rate";
} else {
    $discount_type = "%";
}

// Delete current session data if page is reload or browse other page
if ($page_cart_flag == 0) {
    $sql_delete_session_data = "DELETE FROM temp_order WHERE temp_order_session_id='$session_id'";
    $result_delete_session_data = mysqli_query($con, $sql_delete_session_data);
    if ($result_delete_session_data) {
        $page_cart_flag = 1;
    }
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
        <link href="<?php echo base_url('public/css/animate.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/style.css'); ?>" rel="stylesheet">
        <link href="list_style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
            <?php include base_path('left_menu.php'); ?>
            <div id="page-wrapper" class="gray-bg">
                <?php include base_path('top_bar.php'); ?>
                <div class="wrapper wrapper-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-cart-plus"></i>&nbsp;Point Of Sales</h5>
                                </div>
                                <div class="ibox-content">
                                    <input type="text" id="medicine_name_type" name="medicine_name_type" class="form-control" autofocus="autofocus" placeholder="Type medicine name and press enter" onkeyup="javascript:check_enter_medicine(event, this.value);" />
                                    <div style="margin-top: 20px;" class="table-responsive">
                                        <input type="hidden" id="sub_total_price" value="" />
                                        <span id="sales_table_data">
                                            <table class="table table-responsive table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%">#</th>
                                                        <th style="width: 25%">Medicine Name</th>
                                                        <th style="width: 10%">Expire Date</th>
                                                        <th style="width: 15%">Available Quantity</th>
                                                        <th style="width: 10%">Sell Quantity</th>
                                                        <th style="width: 15%">Sell Price&nbsp;(<?php echo $currency; ?>)</th>
                                                        <th style="width: 15%">Total Price&nbsp;(<?php echo $currency; ?>)</th>
                                                        <th style="width: 5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="temp_table_body">
                                                    <tr>
                                                        <td colspan="8">No data found</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>		
                                                    <tr>       
                                                        <th colspan="6" style="text-align: right">Subtotal&nbsp;(<?php echo $currency; ?>)</th> 
                                                        <td colspan="2">
                                                            <span id="total_sales_subtotal"></span>
                                                        </td>  
                                                    </tr>
                                                    <tr>    
                                                        <th colspan="6" style="text-align: right">Discount (<?php echo $discount_type; ?>)&nbsp;(<?php echo $currency; ?>)</th>  
                                                        <td colspan="2">
                                                            <input class="form-control input-sm" style="width: 100%;" type="number" min="1" />
                                                        </td> 
                                                    </tr>
                                                    <tr>
                                                        <th colspan="6" style="text-align: right">
                                                            Gross Total <small>(including VAT)&nbsp;(<?php echo $currency; ?>)</small>
                                                        </th>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </span>
                                        <button class="btn btn-primary pull-right" type="button" return="false" onclick="javascript:complete_sale();" id="save_data" name="save_data"><i class="fa fa-check"></i> Submit Order</button>
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
        <script src="<?php echo base_url('public/js/custom_script.js'); ?>"></script>
        <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>

        <script type="text/javascript">
                                        var base_url = '<?php echo base_url(); ?>';
                                        $("#menu9").addClass("active");
                                        $("#menu9").parent().parent().addClass("treeview active");
                                        $("#menu9").parent().addClass("in");
        </script>
        <script type="text/javascript">

            $(function () {
                $("#medicine_name_type").autocomplete({
                    source: "<?php echo base_url('views/pos/search.php'); ?>",
                    minLength: 1,
                    select: function (event, ui) {
                        var medicine_id = ui.item.id;
                        console.log(medicine_id);
                    },
                    html: true,
                    open: function (event, ui) {
                        $(".ui-autocomplete").css("z-index", 1000);
                    },
                });
            });
        </script>
    </body>
</html>
