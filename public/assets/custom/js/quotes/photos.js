$(document).on("click", "#photos_btn", function () {
    $("#orderPhotosModal").modal("show");
});

$(document).on("click", "#upload_photos_btn", async function () {
    const formData = new FormData();

    const orderId = $(this).attr("order_id");
    const fileInput = document.getElementById("order_photos");
    const file = fileInput.files[0];

    formData.append("fileType", "1");
    formData.append("orderId", orderId);
    formData.append("file", file);


    const { absolute_path, relative_path, file_id } = await fileUpload(formData);

    let html = `
                            <div class="col-4 file-item">
                                <div class="card">
                                    <img src="${absolute_path}"
                                        class="card-img-top" alt="Order Photo">
                                    <div
                                        class="card-body text-center d-flex align-items-center justify-content-center flex-wrap">

                                        <button type="button" class="btn btn-danger btn-xs delete-order-photo-btn"
                                            order_file_id="${file_id}">Delete</button>
                                        
                                        <div class="checkbox w-25">
                                            <input id="is_no_email_checked" name="is_no_email_checked" type="checkbox" order_file_id="${file_id}" >
                                            <label for="is_no_email_checked" class="ml-2">
                                                    No Email
                                            </label>
                                        </div>

                                        <button type="button" class="btn btn-danger btn-xs rotate-photo-btn"
                                            order_file_id="">Rotate</button>
                                    </div>
                                </div>
                            </div>
                        `;
    $("#photoGallery").append(html);
});

$(document).on("click", ".delete-order-photo-btn", async function () {

    const columnRow = $(this).closest(".file-item");

    const fileId = $(this).attr("order_file_id");

    const response = await fetch(`${BASE_URL}/delete_file`, {
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            "Content-Type": "application/json"
        },
        method: "POST",
        body: JSON.stringify({ fileId: fileId })
    });

    const result = await response.json();

    columnRow.hide(2000, () => {
        columnRow.remove();
    })

});

$(document).on("click", "[name=is_no_email_checked]", async function () {
    $(this).prop("disabled", true);

    const isChecked = $(this).prop("checked");
    const fileId = $(this).closest(".card-body").find(".delete-order-photo-btn").attr("order_file_id");
    const payload = { isChecked, fileId };

    await updateEmail(payload);

    $(this).prop("disabled", false);

});