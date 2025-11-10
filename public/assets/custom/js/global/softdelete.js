$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on("click","#soft-delete",function(){
    let url             = $(this).attr("route");
    let label           = $(this).attr("label");
    let landing_page    = $(this).attr("landing_page");
    Swal.fire({
        title: 'Are you sure?',
        text: `This will deleted the ${label}!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url,
                type: 'DELETE',
                success: function(response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = landing_page; // <— Change this to your target route
                    });
                },
                error: function(xhr) {
                    Swal.fire('Error!', `Could not delete ${label}.`, 'error');
                }
            });
        }
    });
})

