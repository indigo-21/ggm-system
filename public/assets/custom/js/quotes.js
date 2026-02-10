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

    $('.daterange-timestamp').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MMMM DD, YYYY h:mm A'));
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

    $(document).on("click",".remove-additional-cost", function(){
        let cost_additional_section = $(this).closest(".cost-additional-section");
        cost_additional_section.remove();
        updateCostAdditionalSectionElement();
        costCalculation();
    }); 

    $(document).on("click", ".add-additional-cost", function(){
        let last_section = $(".cost-additional-section").length;
        let section_element = last_section  == 0 ? ".letters-section" : ".cost-additional-section";
        let html = `<div class="col-12 row cost-additional-section mt-4">
                        <div class="col-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-simple waves-effect remove-additional-cost d-flex justify-content-center align-items-center"> <i class="zmdi zmdi-minus-circle"></i> </button>
                        </div>
                        <div class="col-6">
                            <textarea name="price_description[${last_section}]" id="price_description[${last_section}]" class="form-control no-resize price-description" data-input-format="[a-zA-Z\s]" rows="5"></textarea>
                        </div>
                        <div class="col-5 d-flex align-items-center">
                            <input type="text" name="price_amount[${last_section}]" id="price_amount[${last_section}]" value="0.00" class="form-control text-right price-amount amount-prices" data-input-format="[a-zA-Z\s]" autocomplete="off" aria-invalid="false">
                        </div>
                    </div>`;
        $(section_element).last().after(html);
        updateCostAdditionalSectionElement();
    });

    function updateCostAdditionalSectionElement(){
        $(".cost-additional-section").each((index, item) => {
            let element = $(item);
            element.find("textarea").attr("name", `price_description[${index}]`).attr("id", `price_description[${index}]`);
            element.find("input").attr("name", `price_amount[${index}]`).attr("id", `price_amount[${index}]`);
        });
    };

    $(document).on("click", "#note_btn", function(){
        if ($.fn.DataTable.isDataTable('#notesTable')) {
            $('#notesTable').DataTable().destroy();
        }
        if (CKEDITOR.instances.order_notes) {
            CKEDITOR.instances.order_notes.destroy(true);
        }

         $('#notesTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        CKEDITOR.replace('order_notes',{
            height: 300,
            toolbar:[
                ["Bold","TextColor"]
            ]
        });

        $("#notesModal").modal("show");
    }); 
    
    $(document).on("click", "#save_order_note_btn", function(){
        let order_id = $(this).attr("order_id");
        CKEDITOR.instances.order_notes.updateElement();
        let data = {
            order_id: order_id,
            order_instruction_note_id: $("[name=order_instruction_note_id]").val(),
            method: "note",
            notes: $("#order_notes").val()
        }
        upsertOrderInstructionNote(data);
    });

    $(document).on("click", ".edit-additional-cost", function(){
        let this_parent = $(this).closest(".modal-body");
        let isNote = $(this).attr("type_of_note") == "note";
        let order_instruction_note_id = $(this).attr("order_instruction_note_id");
        let order_instruction_note = $(this).closest(".order_instruction").find(".order_instruction_note").html();
        if(isNote){
            $("[name=order_instruction_note_id]").val(order_instruction_note_id);
            CKEDITOR.instances.order_notes.setData(order_instruction_note);
        }else{
            $("[name=order_instruction_factory_note_id]").val(order_instruction_note_id);
            CKEDITOR.instances.order_factory_notes.setData(order_instruction_note);
        }
        
    });

    $(document).on("click", "#factory_note_btn", function(){
        if ($.fn.DataTable.isDataTable('#factoryNotesTable')) {
            $('#factoryNotesTable').DataTable().destroy();
        }
        if (CKEDITOR.instances.order_factory_notes) {
            CKEDITOR.instances.order_factory_notes.destroy(true);
        }

        $('#factoryNotesTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        CKEDITOR.replace('order_factory_notes',{
            height: 300,
            toolbar:[
                ["Bold","TextColor"]
            ]
        });

        $("#factoryNotesModal").modal("show");
    });

    $(document).on("click", "#save_factory_note_btn", function(){
        let order_id = $(this).attr("order_id");
        CKEDITOR.instances.order_factory_notes.updateElement();
        let data = {
            order_id: order_id,
            order_instruction_note_id: $("[name=order_instruction_factory_note_id]").val(),
            method: "factory_note",
            notes: $("#order_factory_notes").val()
        }
        upsertOrderInstructionNote(data);
    });

    $(document).on("click", "#receipts_btn", function(){

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

        $(".payment-form").find("input[required], select[required], textarea[required]").each(function(){
            let element = $(this);
            let element_name = element.attr("name");
            let element_value = element.val("").trigger("change");
        });

        $("#paymentModal").modal("show");

    }); 

    $(document).on("click","#save_payment_btn",function(){
        let has_error = false;
        let order_id = $(this).attr("order_id");
        let payment_datetime = $("[name=payment_timestamp]").val();
        let payment_method = $("[name=payment_method]").val();
        let payment_amount = $("[name=payment_amount]").val();
        let payment_comment = $("[name=payment_comment]").val();


        $(".payment-form").find("input[required], select[required], textarea[required]").each(function(){
            let element = $(this);
            let element_name = element.attr("name");
            let element_value = element.val().trim();
            if(element_value == ""){
                has_error = true;
                $(`[name=${element_name}]`).addClass("is-invalid");
            }else{
                $(`[name=${element_name}]`).removeClass("is-invalid");
            }
        });

        if(!has_error){
            let data = { order_id, payment_datetime, payment_method, payment_amount, payment_comment };

            $.ajax({
                url: `${BASE_URL}/order_payment/order_payment_upsert`,
                type: 'POST',
                data,
                dataType: 'json',
                beforeSend:function(){
                    $("#paymentTableBody").html(`<tr><td colspan="6" class="text-center">Loading...</td></tr>`);
                },
                success:function(response){
                    orderPaymentTableData(response);
                },
                error:function(xhr, status, error){
                   console.error(error);   
                }
            })
        }
    });

    $(document).on("click",".order_payment_destroy",function(){
        $order_payment_id = $(this).attr("order_payment_id");
        $.ajax({
            url: `${BASE_URL}/order_payment/order_payment_destroy`,
            type: 'POST',
            data: {id: $order_payment_id},
            dataType: 'json',
            beforeSend:function(){
                $("#paymentTableBody").html(`<tr><td colspan="6" class="text-center">Loading...</td></tr>`);
            },
            success:function(response){
                 orderPaymentTableData(response);
            },
            error:function(xhr, status, error){
                console.error(error);   
            }
        })
    });

    

    function upsertOrderInstructionNote(data){
        let isNote = data.method == "note";
        $.ajax({
            url: `${BASE_URL}/quote/upsert_order_instruction_note`, 
            type: 'POST', 
            data, 
            dataType: 'json', 
            beforeSend:function(){
                $("#notesTableBody").html(`<tr><td colspan="4" class="text-center">Loading...</td></tr>`);
            },
            success: function(response) {

                let html = "";
                
                response.map((note, index) => {
                    let notes = note.notes;
                    let created_by = note.created_by;
                    let updated_by = note.updated_by;
                    let created_at = note.created_at;
                    let updated_at = note.updated_at;
                    let order_instruction_note_id = note.order_instruction_note_id;

                    html += `<tr class="order_instruction">
                                <td class="order_instruction_note">${notes}</td>
                                <td>
                                    <small>Created By: ${created_by} </small>
                                    <br>
                                    <small>Updated By: ${updated_by} </small>
                                </td>
                                <td>
                                    <small>Created At: ${created_at} </small>
                                    <br>
                                    <small>Updated At: ${updated_at} </small>
                                </td>
                                <td>
                                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center edit-additional-cost" order_instruction_note_id="${order_instruction_note_id}">
                                        <i class="zmdi zmdi-border-color"></i>&nbsp;Edit
                                    </button>
                                </td>
                            </tr>`;
                });

                if(isNote){
                    CKEDITOR.instances.order_notes.setData('');
                }else{
                    CKEDITOR.instances.order_factory_notes.setData('');
                }
                $(isNote ? "#notesTableBody" : "#factoryNotesTableBody").html(html);
                $(isNote ? "#order_notes" : "#order_factory_notes" ).val("");
                
            },
            error: function(xhr, status, error) {

                console.error(error);

            }

        });
    }

    function orderPaymentTableData(data = {}){
        let html = "";
        data.map((payment,index) => {
            let payment_method = "Debit Card";

            switch(payment.payment_method){
                case 1:
                    payment_method = "Cash";
                    break;
                case 2:
                    payment_method = "Cheque";
                    break;
                case 3:
                    payment_method = "Credit Card";
                    break;
                case 4:
                    payment_method = "Bank Transfer";
                    break;
            }

            html += `
                <tr>
                    <td>${payment.created_by}</td>
                    <td>${payment.payment_datetime}</td>
                    <td>${payment_method}</td>
                    <td class="text-right">${payment.amount.toFixed(2)}</td>
                    <td>${payment.comment ?? ''}</td>
                    <td class="text-center">
                        <a type="button" class="btn btn-danger btn-xs" href="${BASE_URL}/order_payment/order_payment_print_receipt/${payment.id}" target="_blank">Print Receipt</a>
                        <button type="button" class="btn btn-danger btn-xs order_payment_destroy" order_payment_id="${payment.id}" >Delete</button>
                    </td>
                </tr>   
            `;
        });

        $("#paymentTableBody").html(html);
    }

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