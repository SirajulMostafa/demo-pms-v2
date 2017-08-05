
function check_enter_medicine(e, product) {

    var medicine_name_type = product;

    var keycode = (e.keyCode ? e.keyCode : e.which);
    console.log(keycode);
    console.log(medicine_name_type);
    if (keycode == 13) {
        if (medicine_name_type === '') {
            alert("Enter medicine name");
        } else {
            $("#medicine_name_type").val("");
            $.ajax({
                type: "POST",
                url: base_url + "views/pos/ajax_sales_data.php",
                dataType: "json",
                data: {
                    medicine_name_type: medicine_name_type
                },
                success: function (response) {
                    var obj = response;
                    if (obj.output === "success") {
                        var temp_sales_cart = obj.temp_sales_cart;
                        var temp_sales_sub_total = obj.temp_sales_sub_total;
                        var currency = obj.currency;
                        var store_currency = obj.store_currency;
                        var discount_type = obj.discount_type;
                        console.log(temp_sales_cart);
                        console.log(temp_sales_sub_total);
                        $("#sub_total_price").val(temp_sales_sub_total);
                        generate_table(temp_sales_cart, temp_sales_sub_total, currency, discount_type, store_currency);
                    } else {
                        alert(obj.msg);
                    }
                }
            });
        }
    }
}

function generate_table(temp_sales_cart_obj, temp_sales_sub_total_obj, currency_obj, discount_type, store_currency) {
    var countItem = temp_sales_cart_obj.length;
    var countRow = 1;

    var tableHTML = '';
    if (countItem > 0) {
        tableHTML += '<table class="table table-responsive table-bordered">';
        tableHTML += '<thead>';
        tableHTML += '<tr>';
        tableHTML += '<th style="width: 5%">#</th>';
        tableHTML += '<th style="width: 25%">Medicine Name</th>';
        tableHTML += '<th style="width: 10%">Expire Date</th>';
        tableHTML += '<th style="width: 15%">Available Qty</th>';
        tableHTML += '<th style="width: 10%">Sell Qty</th>';
        tableHTML += '<th style="width: 15%">Sell Price (' + currency_obj + ')</th>';
        tableHTML += '<th style="width: 15%">Total Price (' + currency_obj + ')</th>';
        tableHTML += '<th style="width: 5%">Action</th>';
        tableHTML += '</tr>';
        tableHTML += '</thead>';

        tableHTML += '<tbody id="temp_table_body">';
        $.each(temp_sales_cart_obj, function (key, Event) {
            tableHTML += '<tr id="itemRow_' + Event.temp_order_id + '">';
            tableHTML += '<td style="width: 5%">' + countRow + '</td>';
            tableHTML += '<td style="width: 25%">' + Event.temp_order_medicine_name + '</td>';
            tableHTML += '<td style="width: 10%">' + Event.temp_order_medicine_expire_date + '</td>';
            tableHTML += '<td style="width: 15%">' + Event.medicine_quantity + '</td>';
            tableHTML += '<td style="width: 10%"><input class="form-control input-sm" onchange="javascript:change_quantity(' + Event.temp_order_id + ',' + Event.temp_order_medicine_id + ',' + Event.temp_order_medicine_sell_price + ',' + Event.temp_order_medicine_buy_price + ')" id="temp_order_item_quantity_' + Event.temp_order_id + '" type="number" min="1" value="' + Event.temp_order_qty + '" /></td>';
            tableHTML += '<td style="width: 15%">' + Event.temp_order_medicine_sell_price + '</td>';
            tableHTML += '<td style="width: 15%"><span id="item_total_price_' + Event.temp_order_id + '">' + Event.temp_order_total + '</span></td>';
            tableHTML += '<td style="width: 5%"><a href="javascript:void(0);" style="color: #9b1b25; font-size:15px;" onclick="javascript:delete_item(' + Event.temp_order_id + ');"><i class="fa fa-trash"></i></a></td>';
            tableHTML += '</tr>';

            countRow++;
        });
        tableHTML += '</tbody>';
        tableHTML += '<tfoot>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Subtotal (' + currency_obj + ')</th><td colspan="2"><span id="total_sales_subtotal">' + temp_sales_sub_total_obj + '</span></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Discount (%)&nbsp;(' + currency_obj + ')</th><td colspan="2"><input class="form-control" id="discount_price" style="width: 100%;" type="number" min="1" onkeyup="javascript:discount_calculate(this.value,' + discount_type + ');" /></td></tr>';
        tableHTML += '<tr><th colspan="6" style="text-align: right">Gross Total (including VAT)&nbsp;(' + currency_obj + ')</th><td colspan="2"><span id="gross_total">' + temp_sales_sub_total_obj + '</span></td></tr>';
        tableHTML += '</tfoot>';
        tableHTML += '</table>';

    } else {
        console.log('No item in cart');
    }

    $('#sales_table_data').html(tableHTML);
}



function delete_item(id) {
    var item_id = id;
    if (item_id != '') {
        // post data using ajax
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "views/pos/ajax_delete_item.php",
            data: {
                item_id: item_id
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    var table_empty_html = '<tr><td colspan="8">No data found</td></tr>';
                    var temp_sales_sub_total = obj.temp_sales_sub_total;
                    var item_count = obj.item_count;
                    $("#itemRow_" + item_id).css('background-color', '#FFE0E0');
                    setTimeout(function () {
                        $("#itemRow_" + item_id).fadeOut("slow");
                    }, 1500);
                    $("#sub_total_price").val(temp_sales_sub_total);
                    $("#total_sales_subtotal").text(temp_sales_sub_total);
                    $("#gross_total").text(temp_sales_sub_total);
                    $("#discount_price").val('');
                    if (item_count === 0) {
                        $("#temp_table_body").html(table_empty_html);
                    }
                } else {
                    alert(obj.msg);
                }
            }
        });
    }
}

function change_quantity(order_id, item_id, sell_price, buy_price) {
    var order_id = order_id;
    var item_id = item_id;
    var sell_price = sell_price;
    var buy_price = buy_price;
    var item_qty = $("#temp_order_item_quantity_" + order_id).val();
    console.log(item_qty);
    console.log(order_id);
    console.log(item_id);
    console.log(sell_price);
    if (item_qty === '' || item_qty < 1) {
        alert("Please enter item quantity");
    } else {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: base_url + "views/pos/ajax_change_quantity.php",
            data: {
                order_id: order_id,
                item_id: item_id,
                sell_price: sell_price,
                buy_price: buy_price,
                item_qty: item_qty
            },
            success: function (response) {
                var obj = response;
                if (obj.output === "success") {
                    var sell_price = obj.temp_sales_sell_price;
                    var item_total_price = (sell_price * item_qty);
                    item_total_price = parseFloat(item_total_price).toFixed(2);
                    var sub_total = obj.temp_sales_sub_total;
                    var sub_total_text = sub_total;
                    var item_total_price_text = item_total_price;
                    $("#item_total_price_" + order_id).text(item_total_price_text);
                    $("#total_sales_subtotal").text(sub_total_text);
                    $("#gross_total").text(sub_total_text);
                    $("#sub_total_price").val(sub_total);
                } else {
                    console.log(obj.previous_qty);
                    $("#temp_order_item_quantity_" + order_id).val(obj.previous_qty);
                    alert(obj.msg);
                }
            }
        });
    }
}

function discount_calculate(discount_val, discount_type) {

    if (discount_val > 0 && discount_val != '') {
        var sub_total_price = $("#sub_total_price").val();
        var discount_price = discount_val;
        var calculate_grand_total = 0.00;
        var price_1 = parseFloat(sub_total_price).toFixed(2);//sub total
        var price_2 = parseFloat(discount_price).toFixed(2);// discount 
        if (discount_type == 1) {

            // check discount not more than 100 percent

            if (price_2 > 100.00) {
                alert('Discount cannot be more than 100 percent');
                $("#discount_price").val('');
                $("#gross_total").text(sub_total_price);
            } else {
                var calculate_discount = parseFloat(parseFloat(price_2 / 100).toFixed(2) * parseFloat(price_1).toFixed(2));
                calculate_grand_total = parseFloat(price_1 - calculate_discount).toFixed(2);
                if (parseInt(calculate_grand_total) > parseInt(price_1)) {
                    alert('Amount should not be negative value or greater than total price');
                    $("#discount_price").val('');
                    $("#gross_total").text(sub_total_price);
                } else {
                    $("#gross_total").text(calculate_grand_total);
                }
            }
        } else {

            if (parseInt(price_2) > parseInt(price_1)) {
                alert('Amount should not be negative value or greater than total price');
                $("#discount_price").val('');
                $("#gross_total").text(sub_total_price);
            } else {
                calculate_grand_total = parseFloat(price_1 - price_2).toFixed(2);
                $("#gross_total").text(calculate_grand_total);
            }
        }
    } else {
        var sub_total_price = $("#sub_total_price").val();

        $("#gross_total").text(sub_total_price);
        alert('Enter discount amount');
    }
}

/*
 * Complete sales / cart data
 */
function complete_sale() {
    var confirm_sale = confirm("Are you sure want to confirm payment");
    if (confirm_sale === true) {
        var order_sub_total = $("#sub_total_price").val();
        var order_discount = $("#discount_price").val();
        var order_total = $("#gross_total").text();

        if (order_sub_total != '' && order_total != '') {
            $.ajax({
                type: "POST",
                url: base_url + "views/pos/ajax_complete_sale.php",
                dataType: "json",
                data: {
                    order_sub_total: order_sub_total,
                    order_discount: order_discount,
                    order_total: order_total
                },
                success: function (response) {
                    var obj = response;
                    if (obj.output === "success") {

                        var order_track_id = obj.order_track_id;
                        var invoice_url = base_url + "views/pos/invoice.php?id=" + order_track_id;
                        window.open(invoice_url, '_blank');
                        setTimeout(function () {
                            window.location.reload();
                        }, 100);
                        console.log(obj.msg);
                        console.log(order_track_id);
                    } else {
                        alert(obj.msg);
                    }
                }
            });
        } else {
            alert('Your cart is empty. Please add item in cart.');
        }
    }
}