$(document).on("click", "#inscription_btn", function () {
    if (CKEDITOR.instances.order_inscription) {
        CKEDITOR.instances.order_inscription.destroy(true);
    }
    CKEDITOR.replace('order_inscription', {
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

$(document).on("click", "#save_inscription_btn", async () => {
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
        const { status, message, order_inscription } = await response.json();
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

$(document).on("click", "#save_approval_btn", async () => {
    let this_element = $(this);
    let inscription_id = $("[name=order_inscription_id]").val();
    let inscription_status = $("[name=order_inscription_status]").val();
    let inscription_remarks = $("[name=inscription_remarks]").val();
    let payload = { inscription_id, inscription_status, inscription_remarks };
    this_element.attr("disabled", true);
    try {
        const response = await fetch(`${BASE_URL}/order_inscription/approval`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const { status, message, order_inscription } = await response.json();
        const { reviewed_by, reviewed_at } = order_inscription;

        $("#reviewed_by").text(reviewed_by);
        $("#reviewed_timestamp").text(reviewed_at);

        $("#inscription_message").addClass("text-success");
        $("#inscription_message").html(`<strong>${message}</strong>`);
        status && this_element.attr("disabled", true);

    } catch (error) {
        console.error("Error fetching:", error);
    }
});