$(document).ready(function(){
    jQuery('#datepicker-autoclose1').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: 'yyyy-mm-dd'
    });

    var numberer = 1;
    dataTable = $('#dataTable').on('preXhr.dt', function ( e, settings, data ){
            numberer = data.start + 1;
            $('.row .white-box').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });

            data.date_start = $('#date-range [name=start]').val();
            data.date_end = $('#date-range [name=end]').val();
            data.payment_method_id  = $('#filterSection [name=filter_payment_method_id]').val();

        }).on('xhr.dt', function ( e, settings, json, xhr ){
            $('.row .white-box').unblock();
            if(!document.datatable_search_change_event)
            {
                $("div.dataTables_filter input").unbind();
                $("div.dataTables_filter input").keyup( function (e) {
                    if (e.keyCode == 13) {
                        dataTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;

        }).DataTable({
            language: {
                infoFiltered: ""
            },
            serverSide: true,
            bInfo: false,
            ajax: {
                url: document.app.site_url + '/statement/get',
                type: 'POST'
            },
            columns: [
                {
                    name: 'Number',
                    width: "5%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        return numberer++;
                    }
                },
                { data: "account_name", orderable: false },
                { data: "generated_invoice", orderable: false },
                { data: "transaction_date", orderable: false },
                {
                    data: "transaction_amount", orderable: false,
                    render: function ( data, type, full, meta ) {
                        return rupiah(data);
                    }
                },
                {
                    data: "commit",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var text = '<i class="mdi mdi-shield" style="color: #75EBB1; font-size: 20px;"></i>';
                        if(data == 1) text = '<i class="mdi mdi-shield" style="color: #2F323E; font-size: 20px;"></i>';
                        return text;
                    }
                },
                {
                    data: 'account_statement_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];

                        if(full.commit != 1)
                        {
                            if(document.app.access_list.management_product_upd)
                            {
                                // edit
                                button.push('<button onclick="upd('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                            }

                            if(document.app.access_list.management_product_del)
                            {
                                // hapus
                                button.push('<button onclick="del('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                            }
                        }

                        return button.join('');
                    }
                }
            ]
        });

    $('#btnSaveApp').click(function(e){
        if(formValidator('#appForm')){
            var data = serialzeForm('#appForm');

            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/statement/app/save',
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
                    $('#appForm')[0].reset()
                    dataTable.ajax.reload()
                    $('#componentModal').modal('toggle')
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
});

function add(){
    $('#appForm')[0].reset();
    formPopulate('#appForm', {
        account_statement_id: 0
    })
    $('#componentModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function upd(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/statement/get/byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#appForm', response)
    });

    $('#componentModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function sortInvoiceNumber(id){
    swal({
        title: "Are you sure?",
        text: "Akan mengurutkan semua nomor invoice sesuai tanggal trx, hanya yg belum di commit",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yakin",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/statement/app/sort_invoice_number'
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                dataTable.ajax.reload()
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

function commitInvoiceNumber(id){
    swal({
        title: "Are you sure?",
        text: "Akan mengurutkan semua nomor invoice sesuai tanggal trx, hanya yg belum di commit",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yakin",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/statement/app/commit_invoice_number'
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                dataTable.ajax.reload()
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

function del(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus product ini!",
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
                url: document.app.site_url+'/statement/del/index/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                dataTable.ajax.reload()
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
