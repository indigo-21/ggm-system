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

    $(".daterange-has-current").daterangepicker({
        singleDatePicker: true,
        startDate: moment(),
        locale: {
        format: 'MMMM DD, YYYY'
        }
    });

    $('.daterange').daterangepicker({
        singleDatePicker: true,
        startDate: moment(),
        autoUpdateInput: false, 
        locale: {
            format: 'MMMM DD, YYYY',
            cancelLabel: 'Clear'
        }
    });

    $('.daterange-am-pm').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: false, // enables AM/PM
        timePickerSeconds: false,
        autoUpdateInput: false,  
        startDate: moment(),
        locale: {
            format: 'MMMM DD, YYYY A',
            cancelLabel: 'Clear'
        }
    });

    // $('.invoice-date').daterangepicker({
    //     singleDatePicker: true,
    //     startDate: moment(),
    //     locale: {
    //     format: 'MMMM DD, YYYY'
    //     }
    // });

    $('.month-year').daterangepicker({
        singleDatePicker: true,
        startDate: moment(),
        autoUpdateInput: false,
        locale: {
            format: 'MMMM YYYY',
            cancelLabel: 'Clear'
        }
    });

    // Set value on apply
    $('.daterange').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MMMM DD, YYYY'));
    });

    $('.daterange-am-pm').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MMMM DD, YYYY A'));
    });

    $('.month-year').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MMMM YYYY'));
    });

    // Clear value
    $('.clear-daterange').on('cancel.daterangepicker', function () {
        $(this).val('');
    });


    if ($("[name=special_instruction]").length) {
        CKEDITOR.replace('special_instruction',{
            height: 300,
            toolbar:[
                ["Bold","TextColor"]
            ]
        });
    }

    if ($("[name=customer_note]").length) {
        CKEDITOR.replace('customer_note',{
            height: 300,
            toolbar:[
                ["Bold","TextColor"]
            ]
        });
    }
  

   $(document).on("click", "#submit_form", function(e){
        e.preventDefault();
        
        CKEDITOR.instances.special_instruction.updateElement();
        CKEDITOR.instances.customer_note.updateElement();

        $("#form_validation").submit();
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

    $(document).on("click", ".required-by-radio", function(){
        if($(this).prop("checked")){
            $(this).prop("checked", false);
        }
    });

    $(document).on("click","#searchCustomer", function(){
        $('#customerTable').DataTable();
        $("#customerModal").modal("show");
    })

    $(document).on("click",".existing-customer-btn", function(){
        let { 
                id,
                title,
                firstname,
                lastname,
                salutation,
                address_one,
                address_two,
                city_county,
                postcode,
                customer_contacts
            } = JSON.parse($(this).attr("customer-data"));
            console.log(customer_contacts);
        let email = [], mobile_no = [], tel_no = [];
            
            customer_contacts.forEach((item, index) => {
                if( item.contact_type == 1) email.push(item.contact_value) 
                if( item.contact_type == 2) mobile_no.push(item.contact_value) 
                if( item.contact_type == 3) tel_no.push(item.contact_value) 
            })
        
            $("[name=customer_id]").val(id);
            $("[name=title]").val(title);
            $("[name=firstname]").val(firstname);
            $("[name=lastname]").val(lastname);
            $("[name=salutation]").val(salutation);
            $("[name=address_1]").val(address_one);
            $("[name=address_2]").val(address_two);
            $("[name=city_county]").val(city_county);
            $("[name=post_code]").val(postcode);
            $("[name=email]").val(email.join(";"));
            $("[name=mobile_no]").val(mobile_no.join(";"));
            $("[name=tel_no]").val(tel_no.join(";"));
            $('[name=title]').selectpicker('refresh');
            $("#customerModal").modal("hide");

    });

    $(document).on("change",".with-others-option", function(){
        let thiselement = $(this).attr("name");
        $("[name=for_others_modal]").val("");
        if($(this).val() == "others" || $(this).attr("isother")){
            let labels = $(this).closest(".form-group").find(`label[for='${thiselement}']`);
            let description = $(labels[0]).text();
            $("#forOthersModalLabel").html(`Other ${description}`);
            $("#forOthersModalBody").find("label[for='for_others_modal']").html(`${description}`);
            $("[name=for_others_modal]").attr("placeholder", `Enter other ${description}`)
            $("#otherModalSave").attr("selectfor", thiselement);
            $("#forOthersModal").modal("show");
        }
    });

    $(document).on("click", "#otherModalSave", function(){
        let select_element = $(this).attr("selectfor");
        let value = $("[name=for_others_modal]").val();
        let html = `<option value="${value}" selected isother="true">${value}</option>`;
        // $(`[name=${select_element}]`).append(html);
        $(`[name=${select_element}]`).prepend(html);
        $(`[name=${select_element}]`).selectpicker('refresh');

    });

     $(document).on("keyup", ".amount-prices", function(){
        costCalculation();
    });

    function costCalculation(){
        let letter_no = parseFloat($("[name=letters_no]").val() || 0);
        let letter_amt = parseFloat($("[name=letters_amount]").val() || 0);
        let cemetery_1 = parseFloat($("[name=cemetery_fees_amount_1]").val() || 0);
        let cemetery_2 = parseFloat($("[name=cemetery_fees_amount_2]").val() || 0);
        let letter_total_amt = letter_no * letter_amt;
        let total_amt = 0;
        let vat_rate = parseFloat($("[name=vat_rate]").val() || 0);
        let zero_rated_fees = 0;

        $(".price-amount").each((index, item)=>{
            let element = $(item);
            let temp_amt = element.val() == "" || !element.val()  ? "0" : element.val(); 
            total_amt += parseFloat(temp_amt);
        });

        total_amt += cemetery_1 + cemetery_2;

        $(".zero-rated").each((index, item) => {
            let element = $(item);
            let temp_amt = element.val() == "" || !element.val()  ? "0" : element.val(); 
            zero_rated_fees += parseFloat(temp_amt);
        });

        let net_amt = (total_amt - zero_rated_fees) * 100 / (100 + vat_rate );
        let vat_amt = net_amt * (vat_rate / 100);
        let gross_amt = net_amt + vat_amt + zero_rated_fees;

        $("[name=letters_total_amount]").val(letter_total_amt.toFixed(2));
        $("[name=total]").val(total_amt.toFixed(2));
        $("[name=grand_total_amount]").val(total_amt.toFixed(2));
        $("[name=net_amount]").val(net_amt.toFixed(2));
        $("[name=vat_amount]").val(vat_amt.toFixed(2));
        $("[name=zero_rated_fees]").val(zero_rated_fees.toFixed(2));
        $("[name=gross_amount]").val(gross_amt.toFixed(2));
        
    }

});