function confirmBuy(id){
    swal({
        title: "Apakah anda yakin?",
        text: "Customer mengkonfirmasi akan beli!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Konfirmasi",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            window.location = document.app.site_url+'/orders_v1/follow_up/confirm_buy/'+id;
        }
    });
}

function followUp(id){
    swal({
        title: "Apakah anda yakin?",
        text: "Anda akan memfollow up pesanan!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Follow Up",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            window.location = document.app.site_url+'/orders_v1/app/follow_up/'+id;
        }
    });
}

function saleOrders(id){
    swal({
        title: "Apakah anda yakin?",
        text: "Pesanan telah dibayar dan akan dilanjutkan ke tim logistik!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Ok",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            window.location = document.app.site_url+'/orders_v1/verify/sale/'+id;
        }
    });
}

function cancelOrders(id){
    $('#cancelForm')[0].reset();
    formPopulate('#cancelForm', {
        order_id: id
    })
    $('#notes_etc').hide();
    $('#cancelModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function pendingOrders(id){
    $('#pendingForm')[0].reset();
    formPopulate('#pendingForm', {
        order_id: id
    })
    $('#pending_notes_etc').hide();
    $('#pendingModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function verifyPayment(id){
    swal({
        title: "Apakah anda yakin?",
        text: "Custemer telah membayar tagihan, verifikasi pembayaran ke Finance!",
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
            window.location = document.app.site_url+'/orders_v1/confirm_buy/verify_payment/'+id;
        }
    });
}

$(document).ready(function(){
    $('#cancelForm [name=notes]').change(function(e){
        if($(this).val() == 'dll'){
            $('#notes_etc').show()
            $('#notes_etc [name=notes_value]').val('');
        } else {
            $('#notes_etc').hide();
        }
    });

    $('#pendingForm [name=notes]').change(function(e){
        if($(this).val() == 'dll'){
            $('#pending_notes_etc').show()
            $('#pending_notes_etc [name=notes_value]').val('');
        } else {
            $('#pending_notes_etc').hide();
        }
    });

    $('#btnSaveCancelModal').click(function(){
        var data = serialzeForm('#cancelForm');
        if(data.notes == 'dll' && data.notes_value != '')
        {
            data.notes = data.notes_value;
        } else if (data.notes == 'dll' && data.notes_value == '') {
            alert('Alasan Lainya harap diisi');
            return;
        }

        swal({
            title: "Apakah anda yakin?",
            text: "Anda akan membatalkan pesanan ini!",
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
                    url: document.app.site_url+'/orders_v1/follow_up/cancel',
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
                        $('#cancelForm')[0].reset()
                        $('#cancelModal').modal('toggle')
                    }

                    swal({
                        title: title,
                        text: response.message,
                        timer: timer
                    },function(){
                        window.location.href = document.app.site_url+'/'+document.app.penjualan.orders_state;
                    });
                });
            }
        });
    });

    $('#btnSavePendingModal').click(function(){
        var data = serialzeForm('#pendingForm');
        if(data.notes == 'dll' && data.notes_value != '')
        {
            data.notes = data.notes_value;
        } else if (data.notes == 'dll' && data.notes_value == '') {
            alert('Alasan Lainya harap diisi');
            return;
        }

        swal({
            title: "Apakah anda yakin?",
            text: "Anda akan mempending pesanan ini!",
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
                    url: document.app.site_url+'/orders_v1/follow_up/pending',
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
                        $('#pendingForm')[0].reset()
                        $('#pendingModal').modal('toggle')
                    }

                    swal({
                        title: title,
                        text: response.message,
                        timer: timer
                    },function(){
                        window.location.href = document.app.site_url+'/'+document.app.penjualan.orders_state;
                    });
                });
            }
        });
    });
});
