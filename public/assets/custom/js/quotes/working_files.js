$(document).on("click", "#working_files_btn", function () {
    $("#orderWorkingFilesModal").modal("show");
});

$(document).on("click", "#upload_workingfiles_btn", async function () {
    const formData = new FormData();
    const tableRow = $(".working-files-gallery");

    const orderId = $(this).attr("order_id");
    const fileInput = document.getElementById("workingfiles_file");
    const file = fileInput.files[0];
    const filename = $("#workingfiles_filename").val();
    const description = $("#workingfiles_description").val();

    formData.append("fileType", "3");
    formData.append("orderId", orderId);
    formData.append("file", file);
    formData.append("filename", filename);
    formData.append("description", description);

    const { absolute_path, relative_path, file_id, order_file } = await fileUpload(formData);

    const cleanName = order_file["filename"].replace(/^\d+_/, "");

    const html = `  <div class="col-4 file-item">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">${moment().format('MMMM DD, YYYY h:mm A')}</h5>
                                    </div>
                                    <center class="py-2">
                                        <a href="${absolute_path}">
                                            <i class="zmdi zmdi-file-text"></i> &nbsp; ${cleanName}
                                        </a>
                                    </center>
                                    <div
                                        class="card-body text-center d-flex align-items-center justify-content-center flex-wrap">
                                        <button type="button"
                                            class="btn btn-danger btn-xs delete-order-photo-btn"
                                            order_file_id="${file_id}">Delete</button>
                                        <div class="checkbox w-25">
                                            <input id="is_no_email_checked" name="is_no_email_checked" type="checkbox" order_file_id="${file_id}" >
                                            <label for="is_no_email_checked" class="ml-2">
                                                    No Email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

    tableRow.append(html);

});