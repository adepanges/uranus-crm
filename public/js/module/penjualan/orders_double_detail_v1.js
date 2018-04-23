function pulihkanOrders(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan memulihkan orders ini! dan yakin sisanya akan dibuang",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Pulihkan",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/orders_v1/double/pulihkan/'+id
            })
            .done(function( response) {
                $('.preloader').fadeOut();

                var title = 'Berhasil!';
                if(!response.status) {
                    title = 'Hmmm!';
                }

                if(!response.message) response.message = 'Sedang memuat...'

                document.location.reload()

                swal({
                    title: title,
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: true
                });
            });
        }
    });
}

function trashDoubleOrders(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan membuang semua double orders didalamnya!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Buang",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/orders_v1/double/trash/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                var title = 'Berhasil!';
                if(!response.status){
                    title = 'Gagal!';
                } else {
                    window.location.reload()
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: true
                });
            });
        }
    });
}

function trashOrders(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan membuang orders ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Buang",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/orders_v1/app/trash/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                var title = 'Berhasil!';
                if(!response.status){
                    title = 'Gagal!';
                } else {
                    document.location.reload()
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: true
                });
            });
        }
    });
}
