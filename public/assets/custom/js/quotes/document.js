$(document).on("click", "#documents_btn", function () {
    $("#orderDocumentsModal").modal("show");
});

$(document).on("click", "#upload_documents_btn", async function () {
    const formData = new FormData();
    const tableRow = $("#orderDocumentsTableBody");

    const orderId = $(this).attr("order_id");
    const fileInput = document.getElementById("document_file");
    const file = fileInput.files[0];
    const filename = $("#document_filename").val();
    const description = $("#document_description").val();

    formData.append("fileType", "2");
    formData.append("orderId", orderId);
    formData.append("file", file);
    formData.append("filename", filename);
    formData.append("description", description);

    const { absolute_path, relative_path, file_id, order_file } = await fileUpload(formData);


    let html = `
                            <tr class="file-item">
                                <td>
                                    <a href="${absolute_path}" target="_blank">${order_file["filename"]}</a>
                                </td>
                                <td>${order_file["description"]}</td>
                                <td>
                                    <div class="checkbox w-25">
                                        <input id="is_no_email_checked" name="is_no_email_checked" type="checkbox" order_file_id="${file_id}" >
                                        <label for="is_no_email_checked" class="ml-2">
                                                No Email
                                        </label>
                                    </div>
                                </td>
                                <td>${moment().format('MMMM DD, YYYY h:mm A')}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs delete-order-photo-btn"
                                            order_file_id="${order_file["id"]}">Delete</button>
                                </td>
                            </tr>
                        `;
    tableRow.append(html);
});

$(document).on("click", "[name=document_is_no_email_checked]", async function () {
    $(this).prop("disabled", true);

    const isChecked = $(this).prop("checked");
    const fileId = $(this).closest("tr").find(".delete-order-photo-btn").attr("order_file_id");
    const payload = { isChecked, fileId };

    await updateEmail(payload);

    $(this).prop("disabled", false);
})
