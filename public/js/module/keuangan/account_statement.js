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

    var today = new Date(),
        month = today.getMonth()+1;

    var numberer = 1;
    last_date_commited_trx = `${today.getFullYear()}-${month}-${today.getDate()}`;
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
            last_date_commited_trx = json.last_date_commited_trx;
            $('#datepicker-autoclose1').datepicker('setStartDate', json.last_date_commited_trx);
            $('#appForm [name=transaction_date]').val(json.last_date_commited_trx);

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
                        if(full.transaction_type == 'D') return rupiah(data);
                        else return '';
                    }
                },
                {
                    data: "transaction_amount", orderable: false,
                    render: function ( data, type, full, meta ) {
                        if(full.transaction_type == 'K') return rupiah(data);
                        else return '';
                    }
                },
                {
                    data: "claim",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var text = '';
                        if(data == 1) text = '<i class="fa fa-check" style="color: #75EBB1; font-size: 20px;"></i>';
                        return text;
                    }
                },
                {
                    data: "account_statement_id",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var text = '<i class="mdi mdi-shield" style="color: #FFC36D; font-size: 20px;"></i>';
                        if(full.commit == 1) text = '<i class="mdi mdi-shield" style="color: #2F323E; font-size: 20px;"></i>';
                        return text;
                    }
                },
                {
                    data: 'account_statement_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];

                        if(full.note != ''){
                            button.push(`<span class="mytooltip tooltip-effect-5">
                            <span class="tooltip-item">i</span> <span class="tooltip-content clearfix">
                              <span class="tooltip-text">${full.note}</span> </span>
                            </span>`);
                        }

                        if(full.commit != 1)
                        {
                            if(document.app.access_list.account_statement_upd)
                            {
                                // edit
                                button.push('<button onclick="upd('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                            }

                            if(document.app.access_list.account_statement_upd)
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
            var data = serialzeForm('#appForm'),
                page_info = dataTable.page.info();;

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
                    $('#componentModal').modal('toggle');
                    $('#opsiKreditModal').modal('hide');
                }

                if(data.account_statement_id == 0) dataTable.page( 'last' ).draw( 'page' );
                else dataTable.page( page_info.page ).draw( 'page' );

                swal({
                    title: title,
                    text: response.message,
                    timer: timer,
                    showConfirmButton: showConfirmButton
                });
            });
        }
    })

    $('#btnSaveImportForm').click(function(e){
        if(formValidator('#importForm')){
            var data = new FormData($('#importForm')[0]);

            // $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/statement/import',
                data: data,
                cache: false,
                contentType: false,
                processData: false
            })
            .done(function( response ) {
                // $('.preloader').fadeOut();


                var title = 'Berhasil!',
                    timer = 1000;
                    showConfirmButton = false;

                if(!response.status) {
                    var timer = 3000;
                    title = 'Gagal!';
                    showConfirmButton = true;
                } else if(response.data.key_cache){
                    document.location = document.app.site_url+'/statement/import/process/'+$('#importForm [name=payment_method_id]').val()+'/'+response.data.key_cache;
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

function findParentTrx(){
    var id_inv = btoa($('#find_id_inv').val());

    $('#appForm').block({
        message: '<h3>Please Wait...</h3>',
        css: {
            border: '1px solid #fff'
        }
    });
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/statement/get/by_id_inv/'+id_inv
    })
    .done(function( response ) {
        var el = '';
        $('#appForm').unblock();

        if(response)
        {
            el = `<div class="panel panel-default">
                <input type="hidden" name="parent_statement_id" value="${response.account_statement_id}">
                <div class="panel-heading">
                    <span>${response.generated_invoice}</span>
                    <div class="panel-action">
                        <a href="javascript:void(0)" data-perform="panel-dismiss"><i class="ti-close"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                ${response.transaction_date}
                            </div>
                            <div class="col-md-4">
                                ${response.transaction_type}
                            </div>
                            <div class="col-md-4">
                                `+rupiah(response.transaction_amount)+`
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }
        $('#parent_trx').html(el);
    });
}

function modalKredit(){
    $('#opsiKreditModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#parent_trx').empty();
}

function addDebit(){
    $('#parent_trx').empty();
    $('#appForm')[0].reset();
    formPopulate('#appForm', {
        account_statement_id: 0,
        transaction_date: last_date_commited_trx,
        is_sales: 0,
        transaction_type: 'D'
    })
    $('#componentModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function addPenjualan(){
    $('#parent_trx').empty();
    $('#appForm')[0].reset();
    formPopulate('#appForm', {
        account_statement_id: 0,
        transaction_date: last_date_commited_trx,
        is_sales: 1,
        transaction_type: 'K'
    })
    $('#componentModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function addNonPenjualan(){
    $('#parent_trx').empty();
    $('#appForm')[0].reset();
    formPopulate('#appForm', {
        account_statement_id: 0,
        transaction_date: last_date_commited_trx,
        is_sales: 0,
        transaction_type: 'K'
    })
    $('#componentModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function importData()
{
    $('#importForm')[0].reset();
    $('#importModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function upd(id){
    $('.preloader').fadeIn();
    $('#parent_trx').empty();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/statement/get/byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#appForm', response)

        $('#find_id_inv').val(response.parent_ivoice_number);
        findParentTrx();

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

                dataTable.page( 'last' ).draw( 'page' );

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
        text: "Akan meng-commit semua transaksi yg belum di commit, transaksi yang sudah di commit tidak dapat dihapus maupun dirubah",
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
                url: document.app.site_url+'/statement/app/commit_transaction'
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                dataTable.ajax.reload()
                var title = 'Berhasil!';
                if(!response.status) title = 'Gagal!';

                dataTable.page( 'last' ).draw( 'page' );

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
