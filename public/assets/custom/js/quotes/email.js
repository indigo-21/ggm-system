$(document).on("click","#email_btn", function(){
    if ($.fn.DataTable.isDataTable('#emailTable')) {
        $('#emailTable').DataTable().destroy();
    }       

    const customerEmail = $("#email").val();
    const html = emailTemplate();

    $("#order_email_to").val(customerEmail);
    $("#order_email_body").val(html);


    $('#emailTable').DataTable();
    $("#orderEmailModal").modal("show");       
});

$(document).on("click", ".email-template", function(){
    
    $("#order_email_body").val("");

    $(".email-template").prop("checked", false);
    $(this).prop("checked", true);

    const emailType = $(this).val();
    const html = emailTemplate(emailType);

    
    $("#order_email_body").val(html);

});


$(document).on("click", "#save_order_email_btn", function(){
    const orderId = $(this).attr("order_id");
    const mailTo  = $("#order_email_to").val();
    const mailBody = $("#order_email_body").val();
    const infoTimescale = $("#is_info_timescale_checked").prop("checked");
    const documentInsurance = $("#is_document_insurance_checked").prop("checked");
    const insurance = $("#is_insurance_checked").prop("checked");
    const termsCondition = $("#is_terms_and_conditions_checked").prop("checked");
    const quote = $("#is_quotation_checked").prop("checked");
    const order = $("#is_order_checked").prop("checked");
    const receipt = $("#is_receipts_checked").prop("checked");
    const statement = $("#is_statement_checked").prop("checked");
    const inscription = $("#is_inscription_checked").prop("checked");
    const photo = $("#is_photos_checked").prop("checked");
    const document = $("#is_documents_checked").prop("checked");
    const workingFile = $("#is_working_files_checked").prop("checked");

    const data = {
                    orderId,
                    mailTo,
                    mailBody,
                    infoTimescale,
                    documentInsurance,
                    insurance,
                    inscription,
                    termsCondition,
                    quote,
                    order,
                    receipt,
                    statement,
                    photo,
                    document,
                    workingFile
                };
    sendEmail(data);
});

async function sendEmail(data = {}){
    try {
        const response = await fetch(`${BASE_URL}/send_email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        // const { status, message, order_inscription } = await response.json();
       
    } catch (error) {
        // Handle errors
        console.error("Error fetching items:", error);
    }
}




function emailTemplate(emailType = 0){
    
    const customerName = $("#firstname").val();
    let template = `Dear ${customerName},

Please find attached details of your order. 

If you have any questions, please do not hesitate to contact us.`;

    switch (emailType) {
        case "1":
            // Insurane (Stoneguard)
            template = `Dear ${customerName}, 
            
Thank you for placing your order.

We would like to bring to your attention that the new memorial is entitled to a free six-month insurance policy which will start once the memorial has been fixed.

If you would like to receive this free service, then we will need to get your permission before we can provide your contact details to Stoneguard Insurance.

Please confirm by replying to this email and we will opt you in for the service otherwise there is no need to respond, and we will not share any of your personal details.

If you have any questions, please do not hesitate to contact us.`;

            break;

        case "2":
            // Washdown
            template = `Dear ${customerName}, 

Thank you for placing your order with us. 

We would like to bring to your attention that we offer an annual wash down service. 

We highly recommend this service in order to keep your memorial in good condition. 

The cost is £36.00 for a single memorial and £72.00 for a double memorial.

If this is of interest to you, please reply to this email or call us to let us know which month you would like service to commence.

We will then invoice you annually after the wash down has been completed.

If you have any questions, please do not hesitate to contact us.`;

            break;
        case "3":

            template = `Dear ${customerName},

We hope you and your family are keeping well. It was an honour to help create a lasting memorial for your loved one.

If you feel comfortable, we'd be very grateful if you could share a few words about your experience with us on Google. Your feedback helps other families find a caring and trustworthy service during a difficult time.

You can leave a review here: https://g.page/r/CayLg58mGHtHEAI/review
            
                        `;
            
            break;
        case "4":
            
            template = `Dear ${customerName},

Please find attached details of your order.

If you have any questions, please do not hesitate to contact us.
                    
                    `;

            break;
        case "5":
            
            template = `Dear ${customerName},

We trust that the newly added inscription meets your expectations, and we welcome any further feedback you may have.

We would be grateful if you could share a few words about your experience with us by leaving us a review.

You can leave a review here: https://g.page/r/CayLg58mGHtHEAI/review

Thank you for trusting us.`;

            break;
    
        // default:

        //     template = ``;
            
        //     break;
    }

    return template + ` 

Kind Regards,

Gary Green Memorials`;

}
