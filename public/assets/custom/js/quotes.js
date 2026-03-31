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

    $(document).on("keyup", ".cost-computation", function(){
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
                            <input type="text" name="price_amount[${last_section}]" id="price_amount[${last_section}]" value="0.00" class="form-control text-right price-amount for-total-amount" data-input-format="[a-zA-Z\s]" autocomplete="off" aria-invalid="false">
                        </div>
                    </div>`;
        $(section_element).last().after(html);
        updateCostAdditionalSectionElement();
    });

    $(document).on("click", "#note_btn", function(){
        let order_id = $(this).attr("order_id");
        
        getOrderInstructionNote(order_id, "note");

        if (CKEDITOR.instances.order_notes) {
            CKEDITOR.instances.order_notes.destroy(true);
        }

        CKEDITOR.replace('order_notes',{
            height: 300,
            toolbar:[
                ["Bold","TextColor"]
            ]
        });

        //  $('#notesTable').DataTable({
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print'
        //     ]
        // });

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

    $(document).on("click","#cancel_order_note_btn", function(){
        $("[name=order_instruction_note_id]").val("");
        $("[name=order_notes]").val("");
        CKEDITOR.instances.order_notes.setData('');
        $(this).addClass("d-none");
    });

    $(document).on("click","#cancel_factory_note_btn", function(){
        $("[name=order_instruction_factory_note_id]").val("");
        $("[name=order_factory_notes]").val("");
        CKEDITOR.instances.order_factory_notes.setData('');
        $(this).addClass("d-none");
    });

    $(document).on("click", ".edit-order-instruction-note", function(){
        let this_parent = $(this).closest(".modal-body");
        let isNote = $(this).attr("type_of_note") == "note";
        let order_instruction_note_id = $(this).attr("order_instruction_note_id");
        let order_instruction_note = $(this).closest(".order_instruction").find(".order_instruction_note").html();
        if(isNote){
            $("[name=order_instruction_note_id]").val(order_instruction_note_id);
            CKEDITOR.instances.order_notes.setData(order_instruction_note);
            $("#cancel_order_note_btn").removeClass("d-none");
        }else{
            $("[name=order_instruction_factory_note_id]").val(order_instruction_note_id);
            CKEDITOR.instances.order_factory_notes.setData(order_instruction_note);
            $("#cancel_factory_note_btn").removeClass("d-none");
        }
        
    });

    $(document).on("click", "#factory_note_btn", function(){
        let order_id = $(this).attr("order_id");   
        getOrderInstructionNote(order_id, "factory_note"); 
        if (CKEDITOR.instances.order_factory_notes) {
            CKEDITOR.instances.order_factory_notes.destroy(true);
        }

        CKEDITOR.replace('order_factory_notes',{
            height: 300,
            toolbar:[
                ["Bold","TextColor"]
            ]
        });

        // $('#factoryNotesTable').DataTable({
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'copy', 'csv', 'excel', 'pdf', 'print'
        //     ]
        // });

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

    $(document).on("change",".select-timestamp", function(){
        if($(this).val() == "1"){
            let date_today = moment().format('MMMM DD, YYYY h:mm A');
            let parent = $(this).closest(".form-group");
            parent.find("input[type=hidden]").val(date_today);
            parent.find(".span-timestamp").html(`<strong>-</strong> ${date_today}`); 
        }

    });
    
    // Inscription Related Events
        $(document).on("click","#inscription_btn", function(){
            if (CKEDITOR.instances.order_inscription) {
                CKEDITOR.instances.order_inscription.destroy(true);
            }       
            CKEDITOR.replace('order_inscription',{
                height: 300,
                toolbar: [['Bold', 'Italic', 'Underline', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'Font', 'FontSize', 'Table', 'Source', 'TextColor']]
                // toolbar:[
                //     ["Bold","TextColor"]
                // ]
            });
            $("#inscription_message").removeClass("text-success");
            $("#inscription_message").html("");
            $("#inscriptionModal").modal("show");
        });
        
        $(document).on("click","#save_inscription_btn", async ()=>{
            CKEDITOR.instances.order_inscription.updateElement();
            let order_id = $("#save_inscription_btn").attr("order_id");
            let order_inscription_id = $("[name=order_inscription_id]").val();
            let order_inscription = $("#order_inscription").val();

            let body = {
                        order_inscription_id, 
                        order_id,
                        order_inscription    
                        }
            try {
                const response = await fetch(`${BASE_URL}/order_inscription/upsert`, {
                    method: 'POST',
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json'
                            },
                    body: JSON.stringify(body)
                });
                const {status, message, order_inscription} = await response.json();
                let inscription_id = order_inscription.id;
                let inscription = order_inscription.inscription;
                $("[name=order_inscription_id]").val(inscription_id);
                $("#order_inscription").val(inscription);
                $("#inscription_message").addClass("text-success");
                $("#inscription_message").html(`<strong>${message}</strong>`);
                CKEDITOR.instances.order_inscription.updateElement();
            } catch (error) {
                // Handle errors
                console.error("Error fetching items:", error);
            }


        });

        $(document).on("click","#save_approval_btn", async () => {
            let this_element = $(this);
            let inscription_id = $("[name=order_inscription_id]").val();
            let inscription_status = $("[name=order_inscription_status]").val();
            let inscription_remarks = $("[name=inscription_remarks]").val();
            let payload = {inscription_id, inscription_status, inscription_remarks};
            this_element.attr("disabled", true);
            try {
                const response = await fetch(`${BASE_URL}/order_inscription/approval`,{
                    method: "POST",
                    headers:{
                        "X-CSRF-TOKEN" : $("meta[name='csrf-token']").attr("content"),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                });
                
                const {status, message, order_inscription} = await response.json();
                const {reviewed_by, reviewed_at} = order_inscription;
                
                $("#reviewed_by").text(reviewed_by);
                $("#reviewed_timestamp").text(reviewed_at);

                $("#inscription_message").addClass("text-success");
                $("#inscription_message").html(`<strong>${message}</strong>`);
                status && this_element.attr("disabled", true);

            } catch (error) {
                console.error("Error fetching:", error);
            }
        })
    
    // End Inscripion Related Events



    $(document).on("click","#email_btn", function(){
        if ($.fn.DataTable.isDataTable('#emailTable')) {
            $('#emailTable').DataTable().destroy();
        }       
        $('#emailTable').DataTable();
        $("#orderEmailModal").modal("show");       
    });

    $(document).on("click","#history_btn", function(){
        $("#printingHistoryModal").modal("show");
    });

    $(document).on("click","#photos_btn", function(){
        $("#orderPhotosModal").modal("show");
    });

    $(document).on("click","#documents_btn", function(){
        $("#orderDocumentsModal").modal("show");
    });

    $(document).on("click","#working_files_btn", function(){
        $("#orderWorkingFilesModal").modal("show");
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
                                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center edit-order-instruction-note" order_instruction_note_id="${order_instruction_note_id}">
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

    function getOrderInstructionNote(order_id, type){
        let table_element = type == "note" ? '#notesTable' : '#factoryNotesTable';
        let type_of_note = type == "note" ? "note" : "factory_note";
       
        if ($.fn.DataTable.isDataTable(table_element)) {
            $(table_element).DataTable().destroy();
        }

        $(table_element).DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            processing: true,
            serverSide: false,
            ajax: {
                url: `${BASE_URL}/quote/order_instruction_note`,
                type: 'POST',
                data: function (request) {
                    request.order_id = order_id;
                    request.type = type_of_note;
                }
            },
            columns: [
                { data: 'notes', title: 'Notes', className: 'order_instruction_note' },
                { data: 'created_by', title: 'User' },
                { data: 'created_at', title: 'Timestamp' },
                {
                    data: 'id', // or any field you want to pass to the button
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        // data = row.id
                        return `
                            <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center edit-order-instruction-note" 
                                type_of_note="${type_of_note}" 
                                order_instruction_note_id="${data}">
                                <i class="zmdi zmdi-border-color"></i>&nbsp;Edit
                            </button>
                        `;
                    }
                }
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('order_instruction');
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
        let total_amt = 0;
        let grand_total = 0;
        let gross_amount = 0;
        let letter_no = parseFloat($("[name=letters_no]").val() || 0);
        let letter_amt = parseFloat($("[name=letters_amount]").val() || 0);
        let cemetery_1 = parseFloat($("[name=cemetery_fee_amount_1]").val() || 0);
        let cemetery_2 = parseFloat($("[name=cemetery_fee_amount_2]").val() || 0);
        let discount_amount = parseFloat($("[name=discount_amount]").val() || 0);
        let adjustment_amount = parseFloat($("[name=adjustment]").val() || 0);
        let letter_total_amt = letter_no * letter_amt;
        let vat_rate = parseFloat($("[name=vat_rate]").val() || 0);
        let zero_rated_fees = 0;

        // Compute Number of Letters and display it into Element
        $("[name=letters_total_amount]").val(letter_total_amt.toFixed(2));

        // Get Sum of Total Amount
        $(".for-total-amount").each((index, item)=>{
            let element = $(item);
            let temp_amt = element.val() == "" || !element.val()  ? "0" : element.val(); 
            total_amt += parseFloat(temp_amt);
        });

        // Deduct Discount in Total Amount
        total_amt -= discount_amount;

        // Display Total Amount in Element
        $("[name=total_amount]").val(total_amt.toFixed(2));
        
        // Grand Total Computation and Display in Element
        grand_total = total_amt + cemetery_1 + cemetery_2;
        $("[name=grand_total_amount]").val(grand_total.toFixed(2));

        // Net Amount Computation
            // Get zero rated fees and display in Element
            $(".zero-rated").each((index, item) => {
                let element = $(item);
                let temp_amt = element.val() == "" || !element.val()  ? "0" : element.val(); 
                zero_rated_fees += parseFloat(temp_amt);
            });
            zero_rated_fees += adjustment_amount;
            $("[name=zero_rated_fees]").val(zero_rated_fees.toFixed(2));

        let net_amt = (grand_total - zero_rated_fees) * 100 / (100 + vat_rate );
        $("[name=net_amount]").val(net_amt.toFixed(2));

        // VAT Amount Computation and Display in Element
        let vat_amt = net_amt * (vat_rate / 100);
        $("[name=vat_amount]").val(vat_amt.toFixed(2));

        // Gross Amount Computation and Display in Element
        // gross_amount = net_amt + vat_amt + zero_rated_fees + adjustment_amount;
        gross_amount = net_amt + vat_amt + zero_rated_fees;
        $("[name=gross_amount]").val(gross_amount.toFixed(2));
        
    }

    function updateCostAdditionalSectionElement(){
        $(".cost-additional-section").each((index, item) => {
            let element = $(item);
            element.find("textarea").attr("name", `price_description[${index}]`).attr("id", `price_description[${index}]`);
            element.find("input").attr("name", `price_amount[${index}]`).attr("id", `price_amount[${index}]`);
        });
    };

});