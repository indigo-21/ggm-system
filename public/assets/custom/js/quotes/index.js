$(function () {
    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Initialize No Consecration state on page load
    (function initNoConsecration() {
        let isChecked = $("#no_consecration").is(":checked");
        if (isChecked) {
            $("#consecration_date_wrapper").hide();
            $("[name=consecration_date]").prop("disabled", true);
            $("#no_consecration_options").show();
            // Check if Approx is selected
            if ($("#is_approx").is(":checked")) {
                $("#fixed_required_by_wrapper").show();
                $("[name=fixed_required_by]").prop("disabled", false).prop("required", true);
            } else {
                $("#fixed_required_by_wrapper").hide();
                $("[name=fixed_required_by]").prop("disabled", true).prop("required", false);
            }
        } else {
            $("#consecration_date_wrapper").show();
            $("[name=consecration_date]").prop("disabled", false);
            $("#no_consecration_options").hide();
            $("#fixed_required_by_wrapper").hide();
            $("[name=fixed_required_by]").prop("disabled", true).prop("required", false);
        }
    })();

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
        // startDate: moment(),
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
        CKEDITOR.replace('special_instruction', {
            height: 300,
            toolbar: [
                ["Bold", "TextColor"]
            ]
        });
    }

    if ($("[name=customer_note]").length) {
        CKEDITOR.replace('customer_note', {
            height: 300,
            toolbar: [
                ["Bold", "TextColor"]
            ]
        });
    }


    $(document).on("click", "#submit_form", function (e) {
        e.preventDefault();

        CKEDITOR.instances.special_instruction.updateElement();
        CKEDITOR.instances.customer_note.updateElement();

        // Custom cemetery validation
        if ($("[name=cemetery_id]").val() === "others") {
            let customName = $("#custom_cemetery_name").val().trim();

            if (!customName) {
                $("#custom_cemetery_name-error").text("Please enter a cemetery name.").show();
                return;
            }

            // Client-side duplicate check against existing options
            let isDuplicate = false;
            $("[name=cemetery_id] option").each(function () {
                if ($(this).val() && $(this).val() !== "others" && $(this).val() !== "") {
                    if ($(this).text().trim().toLowerCase() === customName.toLowerCase()) {
                        isDuplicate = true;
                        return false;
                    }
                }
            });

            if (isDuplicate) {
                $("#custom_cemetery_name-error").text("This cemetery already exists. Please select it from the list.").show();
                return;
            }

            // Server-side duplicate check via AJAX
            $.ajax({
                url: `${BASE_URL}/cemetery/check-duplicate`,
                type: 'POST',
                data: {
                    name: customName,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                async: false,
                success: function (response) {
                    if (response.exists) {
                        $("#custom_cemetery_name-error").text("This cemetery already exists. Please select it from the list.").show();
                        isDuplicate = true;
                    }
                },
                error: function () {
                    // If check fails, let server-side validation handle it
                }
            });

            if (isDuplicate) {
                return;
            }

            // Normalize the value (trim whitespace)
            $("#custom_cemetery_name").val(customName);
            $("#custom_cemetery_name-error").hide().text("");
        }

        // Custom burial society organization validation
        if ($("[name=burial_society_organization_id]").val() === "others") {
            let customBsName = $("#custom_burial_society_name").val().trim();

            if (!customBsName) {
                $("#custom_burial_society_name-error").text("Please enter a burial society organization name.").show();
                return;
            }

            // Client-side duplicate check against existing options
            let isBsDuplicate = false;
            $("[name=burial_society_organization_id] option").each(function () {
                if ($(this).val() && $(this).val() !== "others" && $(this).val() !== "") {
                    if ($(this).text().trim().toLowerCase() === customBsName.toLowerCase()) {
                        isBsDuplicate = true;
                        return false;
                    }
                }
            });

            if (isBsDuplicate) {
                $("#custom_burial_society_name-error").text("This burial society organization already exists. Please select it from the list.").show();
                return;
            }

            // Server-side duplicate check via AJAX
            $.ajax({
                url: `${BASE_URL}/burial_society_organization/check-duplicate`,
                type: 'POST',
                data: {
                    name: customBsName,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                async: false,
                success: function (response) {
                    if (response.exists) {
                        $("#custom_burial_society_name-error").text("This burial society organization already exists. Please select it from the list.").show();
                        isBsDuplicate = true;
                    }
                },
                error: function () {
                    // If check fails, let server-side validation handle it
                }
            });

            if (isBsDuplicate) {
                return;
            }

            // Normalize the value (trim whitespace)
            $("#custom_burial_society_name").val(customBsName);
            $("#custom_burial_society_name-error").hide().text("");
        }

        // Validate all masterfile "Others" custom inputs
        let masterfileFields = [
            { select: "material", input: "#custom_material_name", error: "#custom_material_name-error", table: "materials", label: "material" },
            { select: "material_colour", input: "#custom_material_colour_name", error: "#custom_material_colour_name-error", table: "colours", label: "material colour" },
            { select: "base_ledger", input: "#custom_base_ledger_name", error: "#custom_base_ledger_name-error", table: "based_ledgers", label: "base ledger" },
            { select: "letter_type", input: "#custom_letter_type_name", error: "#custom_letter_type_name-error", table: "letter_types", label: "letter type" },
            { select: "accessory", input: "#custom_accessory_name", error: "#custom_accessory_name-error", table: "accessories", label: "accessory" },
            { select: "accessory_colour", input: "#custom_accessory_colour_name", error: "#custom_accessory_colour_name-error", table: "colours", label: "accessory colour" },
        ];

        for (let i = 0; i < masterfileFields.length; i++) {
            let field = masterfileFields[i];
            if ($(`[name=${field.select}]`).val() === "others") {
                let customVal = $(field.input).val().trim();

                if (!customVal) {
                    $(field.error).text(`Please enter a ${field.label} name.`).show();
                    return;
                }

                // Client-side duplicate check
                let mfDuplicate = false;
                $(`[name=${field.select}] option`).each(function () {
                    if ($(this).val() && $(this).val() !== "others" && $(this).val() !== "") {
                        if ($(this).text().trim().toLowerCase() === customVal.toLowerCase()) {
                            mfDuplicate = true;
                            return false;
                        }
                    }
                });

                if (mfDuplicate) {
                    $(field.error).text("This value already exists. Please select it from the list.").show();
                    return;
                }

                // Server-side duplicate check
                $.ajax({
                    url: `${BASE_URL}/masterfile/check-duplicate`,
                    type: 'POST',
                    data: {
                        name: customVal,
                        table: field.table,
                        _token: $('meta[name="csrf-token"]').attr("content")
                    },
                    async: false,
                    success: function (response) {
                        if (response.exists) {
                            $(field.error).text("This value already exists. Please select it from the list.").show();
                            mfDuplicate = true;
                        }
                    },
                    error: function () {
                        // Let server-side validation handle it
                    }
                });

                if (mfDuplicate) {
                    return;
                }

                // Normalize value
                $(field.input).val(customVal);
                $(field.error).hide().text("");
            }
        }

        $("#form_validation").submit();
    });


    //Multi-select
    $('#optgroup').multiSelect({ selectableOptgroup: true });



    // EVENTS

    $(document).on("change", "[name=cemetery_id]", function () {
        let cemetery_id = $(this).val();
        let child_select = "[name=burial_society_organization_id]";
        $(`${child_select}`).val('');

        // Handle custom cemetery input visibility
        if (cemetery_id === "others") {
            // Reset animation so it can re-trigger
            $("#custom_cemetery_wrapper").removeClass("cemetery-animate");
            $("#custom_cemetery_wrapper").show();
            // Trigger reflow to restart animation
            $("#custom_cemetery_wrapper")[0].offsetWidth;
            $("#custom_cemetery_wrapper").addClass("cemetery-animate");
            $("#custom_cemetery_name").prop("required", true).focus();
        } else {
            $("#custom_cemetery_wrapper").hide().removeClass("cemetery-animate");
            $("#custom_cemetery_name").val("").prop("required", false);
            $("#custom_cemetery_name-error").hide().text("");
        }

        // Filter burial society organizations by cemetery
        $(`${child_select}`).prop('disabled', true);
        $(`${child_select}`).selectpicker('destroy');
        $(`${child_select} option`).addClass("d-none");
        if (cemetery_id === "others") {
            // Show only the "Others" option for burial society when cemetery is "Others"
            // $(`${child_select} option[value="others"]`).removeClass("d-none");
            // $(`${child_select}`).val("others");
        } else {
            $(`${child_select} .cemetery_${cemetery_id}`).removeClass("d-none");
            // Also show the "Others" option for predefined cemeteries
            $(`${child_select} option[value="others"]`).removeClass("d-none").addClass(`cemetery_${cemetery_id}`);
        }
        $(`${child_select}`).prop('disabled', false);
        $(`${child_select}`).selectpicker('refresh');

        // Select the first option (placeholder) after filtering
        $(`${child_select}`).val('');
        $(`${child_select}`).selectpicker('refresh');

        // Handle custom burial society visibility based on burial society selection
        if (cemetery_id === "others") {
            // Auto-show custom burial society input since "Others" is auto-selected
            // $(`${child_select}`).val("others");
            // $(`${child_select}`).selectpicker('refresh');
            // showCustomBurialSociety();
        } else {
            // Hide custom burial society input when switching to predefined cemetery
            hideCustomBurialSociety();
        }
    });

    // Handle burial society organization change
    $(document).on("change", "[name=burial_society_organization_id]", function () {
        if ($(this).val() === "others") {
            showCustomBurialSociety();
        } else {
            hideCustomBurialSociety();
        }
    });

    // Client-side duplicate check on blur for immediate feedback
    $(document).on("blur", "#custom_cemetery_name", function () {
        let customName = $(this).val().trim();
        if (!customName) {
            $("#custom_cemetery_name-error").hide().text("");
            return;
        }

        let isDuplicate = false;
        $("[name=cemetery_id] option").each(function () {
            if ($(this).val() && $(this).val() !== "others" && $(this).val() !== "") {
                if ($(this).text().trim().toLowerCase() === customName.toLowerCase()) {
                    isDuplicate = true;
                    return false;
                }
            }
        });

        if (isDuplicate) {
            $("#custom_cemetery_name-error").text("This cemetery already exists. Please select it from the list.").show();
        } else {
            $("#custom_cemetery_name-error").hide().text("");
        }
    });

    // Client-side duplicate check on blur for custom burial society
    $(document).on("blur", "#custom_burial_society_name", function () {
        let customName = $(this).val().trim();
        if (!customName) {
            $("#custom_burial_society_name-error").hide().text("");
            return;
        }

        let isDuplicate = false;
        $("[name=burial_society_organization_id] option").each(function () {
            if ($(this).val() && $(this).val() !== "others" && $(this).val() !== "") {
                if ($(this).text().trim().toLowerCase() === customName.toLowerCase()) {
                    isDuplicate = true;
                    return false;
                }
            }
        });

        if (isDuplicate) {
            $("#custom_burial_society_name-error").text("This burial society organization already exists. Please select it from the list.").show();
        } else {
            $("#custom_burial_society_name-error").hide().text("");
        }
    });

    $(document).on("click", ".required-by-radio", function () {
        if ($(this).prop("checked")) {
            $(this).prop("checked", false);
        }
    });

    // No Consecration checkbox toggle
    $(document).on("change", "#no_consecration", function () {
        if ($(this).is(":checked")) {
            // Hide consecration date field and clear it
            $("#consecration_date_wrapper").hide();
            $("[name=consecration_date]").val("").prop("disabled", true);
            // Show the radio group
            $("#no_consecration_options").show();
            // Hide fixed_required_by by default (only shown when Approx selected)
            $("#fixed_required_by_wrapper").hide();
            $("[name=fixed_required_by]").val("").prop("disabled", true).prop("required", false);
        } else {
            // Show consecration date field
            $("#consecration_date_wrapper").show();
            $("[name=consecration_date]").prop("disabled", false);
            // Hide the radio group and clear selections
            $("#no_consecration_options").hide();
            $("[name=fixed_date]").prop("checked", false);
            // Hide and clear fixed_required_by
            $("#fixed_required_by_wrapper").hide();
            $("[name=fixed_required_by]").val("").prop("disabled", true).prop("required", false);
        }
    });

    // Fixed date radio button change (TBA/Approx/ASAP)
    $(document).on("change", "[name=fixed_date]", function () {
        if ($(this).val() === "is_approx") {
            // Show and enable the approximate date input
            $("#fixed_required_by_wrapper").show();
            $("[name=fixed_required_by]").prop("disabled", false).prop("required", true);
        } else {
            // Hide, disable, and clear the approximate date input
            $("#fixed_required_by_wrapper").hide();
            $("[name=fixed_required_by]").val("").prop("disabled", true).prop("required", false);
        }
    });

    $(document).on("click", "#searchCustomer", function () {
        $('#customerTable').DataTable();
        $("#customerModal").modal("show");
    })

    $(document).on("click", ".existing-customer-btn", function () {
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
            if (item.contact_type == 1) email.push(item.contact_value)
            if (item.contact_type == 2) mobile_no.push(item.contact_value)
            if (item.contact_type == 3) tel_no.push(item.contact_value)
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

    // Configuration for fields with "Others" inline custom inputs
    const othersFieldConfig = {
        material: { wrapper: "#custom_material_wrapper", input: "#custom_material_name", error: "#custom_material_name-error", endpoint: "/masterfile/check-duplicate", table: "materials" },
        material_colour: { wrapper: "#custom_material_colour_wrapper", input: "#custom_material_colour_name", error: "#custom_material_colour_name-error", endpoint: "/masterfile/check-duplicate", table: "colours" },
        base_ledger: { wrapper: "#custom_base_ledger_wrapper", input: "#custom_base_ledger_name", error: "#custom_base_ledger_name-error", endpoint: "/masterfile/check-duplicate", table: "based_ledgers" },
        letter_type: { wrapper: "#custom_letter_type_wrapper", input: "#custom_letter_type_name", error: "#custom_letter_type_name-error", endpoint: "/masterfile/check-duplicate", table: "letter_types" },
        accessory: { wrapper: "#custom_accessory_wrapper", input: "#custom_accessory_name", error: "#custom_accessory_name-error", endpoint: "/masterfile/check-duplicate", table: "accessories" },
        accessory_colour: { wrapper: "#custom_accessory_colour_wrapper", input: "#custom_accessory_colour_name", error: "#custom_accessory_colour_name-error", endpoint: "/masterfile/check-duplicate", table: "colours" },
    };

    $(document).on("change", ".with-others-option", function () {
        let fieldName = $(this).attr("name");
        let config = othersFieldConfig[fieldName];

        if (!config) return; // Not one of our managed fields

        if ($(this).val() === "others") {
            // Show inline custom input with animation
            $(config.wrapper).removeClass("cemetery-animate");
            $(config.wrapper).show();
            $(config.wrapper)[0].offsetWidth; // Trigger reflow
            $(config.wrapper).addClass("cemetery-animate");
            $(config.input).prop("required", true).focus();
        } else {
            // Hide and clear
            $(config.wrapper).hide().removeClass("cemetery-animate");
            $(config.input).val("").prop("required", false);
            $(config.error).hide().text("");
        }
    });

    // Blur duplicate check for all custom "Others" inputs
    $(document).on("blur", ".custom-others-input input[type=text]", function () {
        let input = $(this);
        let customName = input.val().trim();
        let wrapper = input.closest(".custom-others-input");
        let errorLabel = wrapper.find("label.error");
        let selectElement = wrapper.closest(".col-4").find("select");

        if (!customName) {
            errorLabel.hide().text("");
            return;
        }

        // Client-side check against existing dropdown options
        let isDuplicate = false;
        selectElement.find("option").each(function () {
            if ($(this).val() && $(this).val() !== "others" && $(this).val() !== "") {
                if ($(this).text().trim().toLowerCase() === customName.toLowerCase()) {
                    isDuplicate = true;
                    return false;
                }
            }
        });

        if (isDuplicate) {
            errorLabel.text("This value already exists. Please select it from the list.").show();
        } else {
            errorLabel.hide().text("");
        }
    });

    $(document).on("keyup", ".cost-computation", function () {
        costCalculation();
    });

    $(document).on("click", ".remove-additional-cost", function () {
        let cost_additional_section = $(this).closest(".cost-additional-section");
        cost_additional_section.remove();
        updateCostAdditionalSectionElement();
        costCalculation();
    });

    $(document).on("click", ".add-additional-cost", function () {
        let last_section = $(".cost-additional-section").length;
        let section_element = last_section == 0 ? ".letters-section" : ".cost-additional-section";
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

    $(document).on("click", "#note_btn", function () {
        let order_id = $(this).attr("order_id");

        getOrderInstructionNote(order_id, "note");

        if (CKEDITOR.instances.order_notes) {
            CKEDITOR.instances.order_notes.destroy(true);
        }

        CKEDITOR.replace('order_notes', {
            height: 300,
            toolbar: [
                ["Bold", "TextColor"]
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

    $(document).on("click", "#save_order_note_btn", function () {
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

    $(document).on("click", "#cancel_order_note_btn", function () {
        $("[name=order_instruction_note_id]").val("");
        $("[name=order_notes]").val("");
        CKEDITOR.instances.order_notes.setData('');
        $(this).addClass("d-none");
    });

    $(document).on("click", "#cancel_factory_note_btn", function () {
        $("[name=order_instruction_factory_note_id]").val("");
        $("[name=order_factory_notes]").val("");
        CKEDITOR.instances.order_factory_notes.setData('');
        $(this).addClass("d-none");
    });

    $(document).on("click", ".edit-order-instruction-note", function () {
        let this_parent = $(this).closest(".modal-body");
        let isNote = $(this).attr("type_of_note") == "note";
        let order_instruction_note_id = $(this).attr("order_instruction_note_id");
        let order_instruction_note = $(this).closest(".order_instruction").find(".order_instruction_note").html();
        if (isNote) {
            $("[name=order_instruction_note_id]").val(order_instruction_note_id);
            CKEDITOR.instances.order_notes.setData(order_instruction_note);
            $("#cancel_order_note_btn").removeClass("d-none");
        } else {
            $("[name=order_instruction_factory_note_id]").val(order_instruction_note_id);
            CKEDITOR.instances.order_factory_notes.setData(order_instruction_note);
            $("#cancel_factory_note_btn").removeClass("d-none");
        }

    });

    $(document).on("click", "#factory_note_btn", function () {
        let order_id = $(this).attr("order_id");
        getOrderInstructionNote(order_id, "factory_note");
        if (CKEDITOR.instances.order_factory_notes) {
            CKEDITOR.instances.order_factory_notes.destroy(true);
        }

        CKEDITOR.replace('order_factory_notes', {
            height: 300,
            toolbar: [
                ["Bold", "TextColor"]
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

    $(document).on("click", "#save_factory_note_btn", function () {
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


    $(document).on("change", ".select-timestamp", function () {
        if ($(this).val() == "1") {
            let date_today = moment().format('MMMM DD, YYYY h:mm A');
            let parent = $(this).closest(".form-group");
            parent.find("input[type=hidden]").val(date_today);
            parent.find(".span-timestamp").html(`<strong>-</strong> ${date_today}`);
        }

    });



    $(document).on("click", "#history_btn", function () {
        $("#printingHistoryModal").modal("show");
    });






});


async function updateEmail(payload) {
    const response = await fetch(`${BASE_URL}/file_is_email`, {
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json"
        },
        method: "POST",
        body: JSON.stringify(payload)
    });

    const result = await response.json();
}

async function fileUpload(formData) {

    const response = await fetch(`${BASE_URL}/upload_files`, {
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        body: formData
    });

    const result = await response.json();

    return result;
}

function upsertOrderInstructionNote(data) {
    let isNote = data.method == "note";
    $.ajax({
        url: `${BASE_URL}/quote/upsert_order_instruction_note`,
        type: 'POST',
        data,
        dataType: 'json',
        beforeSend: function () {
            $("#notesTableBody").html(`<tr><td colspan="4" class="text-center">Loading...</td></tr>`);
        },
        success: function (response) {

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

            if (isNote) {
                CKEDITOR.instances.order_notes.setData('');
            } else {
                CKEDITOR.instances.order_factory_notes.setData('');
            }
            $(isNote ? "#notesTableBody" : "#factoryNotesTableBody").html(html);
            $(isNote ? "#order_notes" : "#order_factory_notes").val("");

        },
        error: function (xhr, status, error) {

            console.error(error);

        }

    });
}

function getOrderInstructionNote(order_id, type) {
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
                render: function (data, type, row, meta) {
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
        createdRow: function (row, data, dataIndex) {
            $(row).addClass('order_instruction');
        }
    });
}

function orderPaymentTableData(data = {}) {
    let html = "";
    data.map((payment, index) => {
        let payment_method = "Debit Card";

        switch (payment.payment_method) {
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

function costCalculation() {
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
    $(".for-total-amount").each((index, item) => {
        let element = $(item);
        let temp_amt = element.val() == "" || !element.val() ? "0" : element.val();
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
        let temp_amt = element.val() == "" || !element.val() ? "0" : element.val();
        zero_rated_fees += parseFloat(temp_amt);
    });
    zero_rated_fees += adjustment_amount;
    $("[name=zero_rated_fees]").val(zero_rated_fees.toFixed(2));

    let net_amt = (grand_total - zero_rated_fees) * 100 / (100 + vat_rate);
    $("[name=net_amount]").val(net_amt.toFixed(2));

    // VAT Amount Computation and Display in Element
    let vat_amt = net_amt * (vat_rate / 100);
    $("[name=vat_amount]").val(vat_amt.toFixed(2));

    // Gross Amount Computation and Display in Element
    // gross_amount = net_amt + vat_amt + zero_rated_fees + adjustment_amount;
    gross_amount = net_amt + vat_amt + zero_rated_fees;
    $("[name=gross_amount]").val(gross_amount.toFixed(2));

}

function updateCostAdditionalSectionElement() {
    $(".cost-additional-section").each((index, item) => {
        let element = $(item);
        element.find("textarea").attr("name", `price_description[${index}]`).attr("id", `price_description[${index}]`);
        element.find("input").attr("name", `price_amount[${index}]`).attr("id", `price_amount[${index}]`);
    });
};

function showCustomBurialSociety() {
    $("#custom_burial_society_wrapper").removeClass("cemetery-animate");
    $("#custom_burial_society_wrapper").show();
    // Trigger reflow to restart animation
    $("#custom_burial_society_wrapper")[0].offsetWidth;
    $("#custom_burial_society_wrapper").addClass("cemetery-animate");
    $("#custom_burial_society_name").prop("required", true).focus();
}

function hideCustomBurialSociety() {
    $("#custom_burial_society_wrapper").hide().removeClass("cemetery-animate");
    $("#custom_burial_society_name").val("").prop("required", false);
    $("#custom_burial_society_name-error").hide().text("");
}