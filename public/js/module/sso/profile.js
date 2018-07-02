$(document).ready(function(){
    formPopulate('#userForm', document.app.user_profile);

    $('#btnSaveUserForm').click(function(e){
        if(formValidator('#userForm')){
            var data = serialzeForm('#userForm');

            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/user/profile/save',
                data: data
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                var title = 'Berhasil!',
                    timer = 1000;
                    showConfirmButton = false;

                if(!response.status) {
                    var timer = 3000;
                    title = 'Gagal!';
                    showConfirmButton = true;
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: timer,
                    showConfirmButton: showConfirmButton,

                }, function(){
                    if(response.status) {
                        document.location.reload();
                    }
                });
            });
        }
    })
});
