$(function(){

    $('.single-daterange').daterangepicker({
        singleDatePicker: true,
        startDate: false,
        autoUpdateInput: false,
        locale: {
            format: 'MMMM DD, YYYY',
            cancelLabel: 'Clear'
        }
    });

    $('.single-daterange').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MMMM DD, YYYY'));
    });

    $('.clear-daterange').on('cancel.daterangepicker', function () {
        $(this).val('');
    })

    $(document).on("click", ".schedule-table-row", function(){
        const scheduleURL = $(this).attr("href");
        window.location.href = scheduleURL;
    });


});