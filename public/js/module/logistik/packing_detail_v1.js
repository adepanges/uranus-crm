function donePacking(id){
    swal({
        title: "Apakah anda yakin?",
        text: "Pesanan telah di packing",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            window.location = document.app.site_url+'/packing_v1/app/alredy/'+id;
        }
    });
}

function pickUp(id){
    swal({
        title: "Apakah anda yakin?",
        text: "Pesanan telah di pickup oleh ekspedisi",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            window.location = document.app.site_url+'/packing_v1/alredy_pack/pickup/'+id;
        }
    });
}

function onShipping(str_json){
    var data = JSON.parse(atob(str_json))

    $('#shippingForm')[0].reset();
    formPopulate('#shippingForm', data)
    $('#shippingModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$(document).ready(function(){
    $('#btnSaveShipInfoModal').click(function(){
        var data = serialzeForm('#shippingForm'),
            ship_label = $('#shippingForm [name=logistic_id] option:selected').html();

        if(!data.shipping_code) {
            alert('No Resi harap diisi');
            return;
        }

        swal({
            title: "Apakah anda yakin?",
            text: 'Pesanan dalam proses pengiriman oleh '+ship_label+' dengan No. Resi '+data.shipping_code,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ya",
            cancelButtonText: "Batal",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $('.preloader').fadeIn();
                $.ajax({
                    method: "POST",
                    url: document.app.site_url+'/packing_v1/pickup/shipping',
                    data: data
                })
                .done(function( response ) {
                    $('.preloader').fadeOut();
                    var title = 'Berhasil!',
                        timer = 1000;

                    if(!response.status) {
                        var timer = 3000;
                        title = 'Gagal!';
                    } else {
                        $('#shopingCartModal').modal('toggle')
                    }

                    swal({
                        title: title,
                        text: response.message,
                        timer: timer
                    },function(){
                        document.location = document.app.site_url +'/'+ document.app.logistics.packing_state;
                    });
                });
            }
        });
    });
});
