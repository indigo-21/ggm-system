$(function(){
    $(document).on("click", "#email, #tel_no, #mobile_no", function(){
        // let thisType = $(this).attr("data-type");
        let thisType = $(this).attr("name");
        let thisVal = $(this).val().length > 0 ? $(this).val().split(";") : [];
        formModalContent(thisType, thisVal);
    })

    $(document).on("click", ".append-modal-form-content", function(){
        let formType = $(this).attr("data-type");
        let html = bodyModalContent(formType);
        $(`#form-modal-${formType}`).append(html);
    });

    $(document).on("click", ".remove-modal-form-content", function(){
        $(this).closest(".content-item").hide(1000).remove();
    });

    $(document).on("click","#formModalSave", function(){
        let formType = $(this).attr("data-type");
        let contentValue = [];
        let contentItems = $(this).closest(`.modal-content`).find(".dynamic_input");

        contentItems.each((index, items) =>{
            contentValue.push($(items).val().trim());
        });

         $("#formModal").on("hidden.bs.modal", function () {
            $(`#${formType}`).val(contentValue.join(";"));
        });

        $("#formModal").modal("hide");
    });

    function formModalContent(formType = "email", values = []){
        let html, type;
        $("#formModalSave").attr("data-type", "");
        // formType = email
        type = toCapitalCase(formType.replace("_", " "));
        contentItems = bodyModalContent(formType);
        if(values.length > 0){
            contentItems = "";
            values.forEach((item, index) => {
                contentItems += bodyModalContent(formType, item);
            })
        }
        html = `<div class="form-group form-float" id="form-modal-${formType}">
                        ${contentItems}
                    </div>
                </div>`;
        
        

        html+= `<button type="button" class="btn btn-default waves-effect append-modal-form-content" data-type="${formType}">Add ${type}</button>`;
        
        $("#formModalBody").html(html);
        $("#formModalLabel").html(`List of ${type}`);
        $("#formModalSave").attr("data-type", formType);
        $("#formModal").modal("show");
    }

    function bodyModalContent(formType = "email", value = false){
        let html; 
        let type = toCapitalCase(formType.replace("_", " "));
        html = `    <div class="content-item d-flex justify-content-between align-items-center my-1">
                                <div class="left mr-1 w-100">
                                    <input type="text" name="dynamic_input" id="dynamic_input" value="${value || ""}" class="form-control w-100 dynamic_input" placeholder="Enter ${type}">
                                </div>
                                <div class="right ml-1">
                                    <button type="button" class="btn btn-danger waves-effect remove-modal-form-content" data-type="${formType}">Remove</button>
                                </div>
                            </div>`;
        return html;
    }
})