<?php
include '../../config/config.php';
check_admin_login();
check_permission();
$store_info = get_store_info();
$expense_criteria = array();
$expense_amount = array();
$expense_created_by = get_session('admin_id');
$expense_created_on = date('Y-m-d H:i:s');
$expense_date = date('Y-m-d');
$expense_flag = 0;
if (isset($_POST['btn_save_expense'])) {
    extract($_POST);

    for ($i = 0; $i < count($expense_criteria); $i++) {

        $variable = '';
        $variable .= 'expense_criteria = "' . $expense_criteria[$i] . '"';
        $variable .= ',expense_amount = "' . $expense_amount[$i] . '"';
        $variable .= ',expense_created_by = "' . $expense_created_by . '"';
        $variable .= ',expense_created_on = "' . $expense_created_on . '"';
        $variable .= ',expense_date = "' . $expense_date . '"';

        $sql_insert_expense = "INSERT INTO expense SET $variable";
        $result_insert_expense = mysqli_query($con, $sql_insert_expense);
        $expense_flag++;
    }
    if ($expense_flag > 0) {
        $success = "Expense added successfully";
    } else {
        $error = "Something went wrong.";
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
        <link href="<?php echo base_url('public/css/custom.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('public/css/plugins/datapicker/datepicker3.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <div id="wrapper">
            <?php include base_path('left_menu.php'); ?>
            <div id="page-wrapper" class="gray-bg">
                <?php include base_path('top_bar.php'); ?>
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <?php if ($error): ?>
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($success): ?>
                                    <div class="alert alert-success alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        <?php echo $success; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="ibox-title">
                                    <h5><i class="fa fa-plus"></i>&nbsp;Add Expense</h5>
                                </div>
                                <div class="ibox-content">
                                    <form id="target"  method="POST" action="">
                                        <div class="row">
                                            <div id="DivRow" class="DivRow">
                                                <div class="RowDiv_0">
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-5">
                                                            <div class="form-group">
                                                                <label for="expense_criteria">Expense Criteria<b class="required_mark">*</b></label>
                                                                <input id="expense_criteria_0" name="expense_criteria[]" class="form-control" type="text" value="" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <div class="form-group">
                                                                <label for="expense_amount">Amount<b class="required_mark">*</b></label>
                                                                <input type="number" id="expense_amount_0" name="expense_amount[]" value="" class="form-control" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <a href="javascript:void(0);" id="btn_add_row" name="btn_add_row"><span class="label label-success" style="background-color: black !important;"><i class="fa fa-plus"></i>&nbsp;Add Row</span></a>
                                            &nbsp;<small style="font-style: italic;color: red;font-weight: bold;">[ * marked fields are mandatory ]</small>
                                        </div>
                                        <br><br>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="col-sm-6">
                                                    <button class="btn btn-primary" type="submit" name="btn_save_expense" id="btn_save_expense"><i class="fa fa-check"></i> Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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

        <script type="text/javascript">
            $("#menu7").addClass("active");
            $("#menu7").parent().parent().addClass("treeview active");
            $("#menu7").parent().addClass("in");
        </script>
        <script type="text/javascript">
            function removeElement(id) {
                var set_id = "#element_" + id;
                $(set_id).remove();
            }
            $(document).ready(function () {
                var id = 0;
                $("#btn_add_row").click(function () {
                    var status = 0;
                    if ($("#expense_criteria_" + id).val() === '') {
                        status++;
                        $("#expense_criteria_" + id).css("borderColor", "red");
                    } else {
                        $("#expense_criteria_" + id).css("borderColor", "#d2d6de");
                    }
                    if ($("#expense_amount_" + id).val() === '') {
                        status++;
                        $("#expense_amount_" + id).css("borderColor", "red");
                    } else {
                        $("#expense_amount_" + id).css("borderColor", "#d2d6de");
                    }
                    $("#expense_criteria_" + id).keyup(function () {
                        $("#expense_criteria_" + id).css("borderColor", "#d2d6de");
                    });
                    $("#expense_amount_" + id).keyup(function () {
                        $("#expense_amount_" + id).css("borderColor", "#d2d6de");
                    });
                    var fieldHTML = '';
                    if (status == 0) {
                        $("#expense_criteria_" + id).css("borderColor", "#d2d6de");
                        $("#expense_amount_" + id).css("borderColor", "#d2d6de");
                        id++;
                        fieldHTML += '<div id="element_' + id + '">'; //1
                        fieldHTML += '<div class="clearfix"></div>';
                        fieldHTML += '<div class="RowDiv_' + id + '">'; //2
                        fieldHTML += '<div class="col-sm-12">';
                        fieldHTML += '<div class="col-sm-5">';
                        fieldHTML += '<div class="form-group">';
                        fieldHTML += '<label for="expense_criteria">Expense Criteria<b class="required_mark">*</b></label>';
                        fieldHTML += '<input id="expense_criteria_' + id + '" name="expense_criteria[]" class="form-control" type="text" value="" required />';
                        fieldHTML += '</div>';
                        fieldHTML += '</div>';
                        fieldHTML += '<div class="col-sm-5">';
                        fieldHTML += '<div class="form-group">';
                        fieldHTML += '<label for="expense_amount">Amount<b class="required_mark">*</b></label>';
                        fieldHTML += '<input id="expense_amount_' + id + '" name="expense_amount[]" class="form-control" type="number" value="" required />';
                        fieldHTML += '</div>';
                        fieldHTML += '</div>';
                        fieldHTML += '<div class="col-sm-2">';
                        fieldHTML += '<div class="form-group">';
                        fieldHTML += '<label for="">&nbsp;</label><br>';
                        fieldHTML += '<a href="javascript:void(0)" onclick="removeElement(' + id + ')"><span id="remove_' + id + '"><i class="fa fa-remove" style="color:red"></i></span></a>';
                        fieldHTML += '</div>';
                        fieldHTML += '</div>';
                        fieldHTML += '</div>'; //2
                        fieldHTML += '</div>'; //1

                        $("#DivRow").append(fieldHTML);
                    }
                });
                $("#btn_save_expense").click(function () {
                    var flag = 0;
                    if ($("#expense_criteria_" + id).val() === '') {
                        flag++;
                        $("#expense_criteria_" + id).css("borderColor", "red");
                    }
                    if ($("#expense_amount_" + id).val() === '') {
                        flag++;
                        $("#expense_amount_" + id).css("borderColor", "red");
                    }
                    if (flag === 0) {
                        $("#target").submit();
                    }
                });
            });
        </script>
    </body>
</html>
