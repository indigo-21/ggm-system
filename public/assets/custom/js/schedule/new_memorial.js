$(function(){
    $(document).on("click", "#is_inscription_factory_approved", function(){
        const isChecked = $(this).prop("checked");
        const timestampElement = $("[name=is_inscription_factory_timestamp]");
        
        timestampElement.val("");
        
        if(isChecked){
            timestampElement.val(moment());
        }
        
    });
});