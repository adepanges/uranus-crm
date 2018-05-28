$(document).ready(function(){
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    // var numberer = 1;
    // dataTable = $('#dataTable').on('preXhr.dt', function ( e, settings, data ){
    //         numberer = data.start + 1;
    //         $('.row .white-box').block({
    //             message: '<h3>Please Wait...</h3>',
    //             css: {
    //                 border: '1px solid #fff'
    //             }
    //         });
    //     }).on('xhr.dt', function ( e, settings, json, xhr ){
    //         $('.row .white-box').unblock();
    //         if(!document.datatable_search_change_event)
    //         {
    //             $("div.dataTables_filter input").unbind();
    //             $("div.dataTables_filter input").keyup( function (e) {
    //                 if (e.keyCode == 13) {
    //                     dataTable.search( this.value ).draw();
    //                 }
    //             });
    //         }
    //         document.datatable_search_change_event = true;
    //     }).DataTable({
    //         language: {
    //             infoFiltered: ""
    //         },
    //         serverSide: true,
    //         bInfo: false,
    //         ajax: {
    //             url: document.app.site_url + '/customer/get',
    //             type: 'POST'
    //         },
    //         aaSorting: [[1, 'asc']],
    //         columns: [
    //             {
    //                 name: 'Number',
    //                 width: "5%",
    //                 orderable: false,
    //                 render: function ( data, type, full, meta ) {
    //                     return numberer++;
    //                 }
    //             },
    //             { data: "full_name" },
    //             { data: "telephone" },
    //             { data: "email" },
    //             { data: "gender" },
    //             { data: "city" },
    //             {
    //                 data: 'customer_id',
    //                 width: "12%",
    //                 orderable: false,
    //                 render: function ( data, type, full, meta ) {
    //                     var button = [];
    //
    //                     button.push('<a href="'+document.app.site_url + '/customer/detail/index/' + data + '" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>');
    //
    //                     if(document.app.access_list.crm_customer_upd)
    //                     {
    //                         // edit
    //                         button.push('<button onclick="upd('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
    //                     }
    //
    //                     if(document.app.access_list.crm_customer_del)
    //                     {
    //                         // hapus
    //                         button.push('<button onclick="del('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
    //                     }
    //
    //                     return button.join('');
    //                 }
    //             }
    //         ]
    //     });

    $('#btnSaveInfoPribadi').click(function(e){
        if(formValidator('#infoPribadiForm')){
            var data = serialzeForm('#infoPribadiForm');

            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/customer/app/save',
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
                } else {
                    document.location.reload()
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: timer,
                    showConfirmButton: showConfirmButton
                });
            });
        }
    })

    $('#btnSavePhoneNumber').click(function(e){
        if(formValidator('#phoneNumberForm')){
            var data = serialzeForm('#phoneNumberForm');

            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/customer/app/save_phone',
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
                } else {
                    document.location.reload()
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: timer,
                    showConfirmButton: showConfirmButton
                });
            });
        }
    })

    $('#btnResetInfoPribadi').click(function(e){
        formPopulate('#infoPribadiForm', customer_data)
    });

    $('.checkPrimary').click(function(){
        var customer_phonenumber_id = $(this).val();
        swal({
            title: "Are you sure?",
            text: "Anda akan menjadikan nomor ini sebagai nomor primer!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            confirmButtonText: "Ok",
            cancelButtonText: "Batal",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $('.preloader').fadeIn();
                $.ajax({
                    method: "POST",
                    url: document.app.site_url+'/customer/app/set_phone_primary',
                    data: {
                        customer_id: $('#customer_id').val(),
                        customer_phonenumber_id: customer_phonenumber_id
                    }
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
                    } else {
                        document.location.reload()
                    }

                    swal({
                        title: title,
                        text: response.message,
                        timer: timer,
                        showConfirmButton: showConfirmButton
                    });
                });
            }
        });
    })
});

function addPhoneNumber(){
    $('#phoneNumberForm')[0].reset();
    formPopulate('#phoneNumberForm', {
        customer_phonenumber_id: 0,
        customer_id: $('#customer_id').val()
    })
    $('#phoneNumberModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function updPhoneNumber(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/customer/get/phone_byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#phoneNumberForm', response)
    });

    $('#phoneNumberModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function delPhoneNumber(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus nomor ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/customer/del/phone/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                document.location.reload()
                var title = 'Berhasil!';
                if(!response.status) title = 'Gagal!';

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
