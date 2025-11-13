$(function () {
    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    
    $('#form_validation').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        }
    });

    $('.daterange').daterangepicker({
        singleDatePicker: true,
        startDate: moment(),
        locale: {
        format: 'MMMM DD, YYYY'
        }
    });

    $('.invoice-date').daterangepicker({
        singleDatePicker: true,
        startDate: "",
        locale: {
        format: 'MMMM DD, YYYY'
        }
    });

  
    CKEDITOR.replace('special_instructions',{
        height: 300,
        toolbar:[
            ["Bold","TextColor"]
        ]
    });

     CKEDITOR.replace('customer_notes',{
        height: 300,
        toolbar:[
            ["Bold","TextColor"]
        ]
    });

   
        // let start   = moment().startOf('month')
        // let end     = moment().endOf('month');

        // function predifinedDateRange(start, end) {
        //     $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        // }

        // $('#daterange').daterangepicker({
        //     startDate: start,
        //     endDate: end,
        //     ranges: {
        //     'Today': [moment(), moment()],
        //     'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        //     'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        //     'This Month': [moment().startOf('month'), moment().endOf('month')],
        //     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        //     },
        //     locale: {
        //         format: 'MMMM DD, YYYY'
        //     }
        // }, predifinedDateRange);

        // predifinedDateRange(start, end);


    //Multi-select
    $('#optgroup').multiSelect({ selectableOptgroup: true });



    // EVENTS

    $(document).on("change","[name=cemetery_id]", function(){
        let cemetery_id = $(this).val();
        let child_select = "[name=burial_society_organization_id]";
        $(`${child_select}`).prop('disabled', true);
        $(`${child_select}`).selectpicker('destroy');
        $(`${child_select} option`).addClass("d-none");
        $(`${child_select} .cemetery_${cemetery_id}`).removeClass("d-none");
        $(`${child_select}`).prop('disabled', false);
        $(`${child_select}`).selectpicker('refresh');
    });

});