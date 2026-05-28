$(document).on("click", "#receipts_btn", function () {

    if ($.fn.DataTable.isDataTable('#paymentTable')) {
        $('#paymentTable').DataTable().destroy();
    }

    $('#paymentTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('.daterange-timestamp').each(function () {
        if ($(this).data('daterangepicker')) {
            $(this).data('daterangepicker').remove();
            $(this).off('.daterangepicker');
        }
    });

    $('.daterange-timestamp').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: false,
        timePickerSeconds: false,
        autoUpdateInput: false,
        startDate: moment(),
        locale: {
            format: 'MMMM DD, YYYY h:m A',
            cancelLabel: 'Clear'
        }
    });

    $(".payment-form").find("input[required], select[required], textarea[required]").each(function () {
        let element = $(this);
        let element_name = element.attr("name");
        let element_value = element.val("").trigger("change");
    });

    $("#paymentModal").modal("show");

});

$(document).on("click", "#save_payment_btn", function () {
    let has_error = false;
    let order_id = $(this).attr("order_id");
    let payment_datetime = $("[name=payment_timestamp]").val();
    let payment_method = $("[name=payment_method]").val();
    let payment_amount = $("[name=payment_amount]").val();
    let payment_comment = $("[name=payment_comment]").val();


    $(".payment-form").find("input[required], select[required], textarea[required]").each(function () {
        let element = $(this);
        let element_name = element.attr("name");
        let element_value = element.val().trim();
        if (element_value == "") {
            has_error = true;
            $(`[name=${element_name}]`).addClass("is-invalid");
        } else {
            $(`[name=${element_name}]`).removeClass("is-invalid");
        }
    });

    if (!has_error) {
        let data = { order_id, payment_datetime, payment_method, payment_amount, payment_comment };

        $.ajax({
            url: `${BASE_URL}/order_payment/order_payment_upsert`,
            type: 'POST',
            data,
            dataType: 'json',
            beforeSend: function () {
                $("#paymentTableBody").html(`<tr><td colspan="6" class="text-center">Loading...</td></tr>`);
            },
            success: function (response) {
                orderPaymentTableData(response);
                window.location.reload();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        })
    }
});

$(document).on("click", ".order_payment_destroy", function () {
    $order_payment_id = $(this).attr("order_payment_id");
    $.ajax({
        url: `${BASE_URL}/order_payment/order_payment_destroy`,
        type: 'POST',
        data: { id: $order_payment_id },
        dataType: 'json',
        beforeSend: function () {
            $("#paymentTableBody").html(`<tr><td colspan="6" class="text-center">Loading...</td></tr>`);
        },
        success: function (response) {
            orderPaymentTableData(response);
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    })
});