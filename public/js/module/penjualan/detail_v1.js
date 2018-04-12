function addProductList(){
    productTable.ajax.reload();
    $('#addProductListModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function addListProduct(data)
{
    data = JSON.parse(atob(data));
    data.qty = prompt("Berapa jumlah "+data.name+" yang dipesan", "1");
    swal({
        title: "Apakah anda yakin?",
        text: "Tambahkan "+data.name+" dengan jumlah "+data.qty+" kedalam cart",
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
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/orders_v1/app/addon_shopping_info',
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
                    document.location.reload();
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: timer
                },function(){

                });
            });
        }
    });
}

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

function pulihkanTrashOrders(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan memulihkan orders trash ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Pulihkan",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            window.location = document.app.site_url+'/orders_v1/trash/pulihkan/'+id;
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
    $('#saleModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function addonShoopingCart(){
    $('#addonShoopingCartModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function deleteCart(id){
    swal({
        title: "Apakah anda yakin?",
        text: "Anda akan menghapus product/biaya tersebut!",
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
            window.location = document.app.site_url+'/orders_v1/app/del_addon_shopping_info/'+document.app.penjualan.orders.order_id+'/'+id;
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

function updateCustomerInfo(id){
    $('#updateCustomerInfoForm')[0].reset();
    formPopulate('#updateCustomerInfoForm', document.app.penjualan.orders);
    formPopulate('#updateCustomerInfoForm', document.app.penjualan.orders.customer_info);
    formPopulate('#updateCustomerInfoForm', document.app.penjualan.orders.customer_address);
    $('#provinsi_id_select').change()

    $('#updateCustomerModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function updateShoopingCart(id){
    $('#shopingCartModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    initShoopingCart();
}

$('#shopingCartForm select[name=product_package_id]').on('change', function(){
    initShoopingCart();
});

function initShoopingCart(){
    var data = serialzeForm('#shopingCartForm');
    $('#shopingCartForm select[name=product_package_id] option').each(function(key, el){
        if($(el).val() == data.product_package_id){
            var cart = atob($(el).attr('data')),
                detail = '', package_ = JSON.parse(cart), cart = [];

            if(package_){
                if(Array.isArray(package_.product_list)){
                    var total_retail_price = 0;
                    package_.product_list.forEach(function(val, key){
                        var retail_price = '';
                        if(package_.price_type == 'RETAIL') {
                            retail_price = rupiah(val.price);
                            total_retail_price += (val.price * 1);
                        }

                        cart.push(`<div class="row" style="padding-left: 40px;">
                            <div class="col-md-5" style="border-bottom: 1px dotted #000;">
                                <h5>${val.name}</h5>
                            </div>
                            <div class="col-md-2" style="border-bottom: 1px dotted #000;">
                                <h5>Qty. ${val.qty}</h5>
                            </div>
                            <div class="col-md-5">
                                <h6>${retail_price}</h6>
                            </div>
                        </div>`);
                    });
                }

                var package_price = '';
                if(package_.price_type == 'PACKAGE') package_price = rupiah(package_.price);
                else package_price = `(${package_.price_type}) ` + rupiah(total_retail_price);

                detail = `<div class="row" style="margin-left: 7px;">
                    <div class="col-md-6">
                        <h3>${package_.name}</h3>
                    </div>
                    <div class="col-md-6">
                        <h3>${package_price}</h3>
                    </div><br>
                    ${cart.join('')}
                </div>`;
                $('#detailCart').html(detail);

            }
        }
    });
}

function updInvoice(){
    $('#updInvoiceModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$(document).ready(function(){
    var numbererProduct = 1;
    productTable = $('#productTable').on('preXhr.dt', function ( e, settings, data ){
        numbererProduct = data.start + 1;
        $('#productTable').block({
            message: '<h3>Please Wait...</h3>',
            css: {
                border: '1px solid #fff'
            }
        });
    }).on('xhr.dt', function ( e, settings, json, xhr ){
        $('#productTable').unblock();
        if(!document.datatable_search_change_event)
        {
            $("div.dataTables_filter input[aria-controls=productTable]").unbind();
            $("div.dataTables_filter input[aria-controls=productTable]").keyup( function (e) {
                if (e.keyCode == 13) {
                    productTable.search( this.value ).draw();
                }
            });
        }
        document.datatable_search_change_event = true;
    }).DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian.json'
        },
        serverSide: true,
        bInfo: false,
        ajax: {
            url: document.app.site_url + '/product/get',
            type: 'POST'
        },
        columns: [
            {
                name: 'Number',
                width: "5%",
                orderable: false,
                render: function ( data, type, full, meta ) {
                    return numbererProduct++;
                }
            },
            {
                data: "name",
                render: function ( data, type, full, meta ) {
                    return `${full.code} - ${full.merk} - ${full.name}`;
                }
            },
            {
                data: "price",
                render: function ( data, type, full, meta ) {
                    return rupiah(data);
                }
            },
            {
                data: 'product_id',
                width: "12%",
                orderable: false,
                render: function ( data, type, full, meta ) {
                    var button = [];
                    var data_json = full;
                    data_json.order_id = document.app.penjualan.orders.order_id;
                    button.push('<button onclick=addListProduct("'+btoa(JSON.stringify(data_json))+'") type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>');
                    return button.join('');
                }
            }
        ]
    });

    jQuery('#datepicker-autoclose2').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    jQuery('#datepicker-autoclose1').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    $('#datepicker-autoclose1').change(function(e){
        var str = $(this).val(),
            inv_first = '';
        str = str.replace(/-/g, "");
        inv_first = `DKI/${str}/`;

        $('#basic-addon1').html(inv_first);
        $('#saleForm [name=invoice_first]').val(inv_first)
    });

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

    $('#addonShoopingCartForm [name=name]').change(function(e){
        var name_val = $(this).val();
        if(name_val == '') {
            $('#addonShoopingCartForm [name=name_other]').val('');
            $('#otherName').show();
        } else {
            $('#otherName').hide();
        }
    });

    $('#btnSaveUpdInvoiceModal').click(function(){
        if(formValidator('#updInvoiceForm')){
            var data = serialzeForm('#updInvoiceForm');
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/orders_v1/sale/upd_invoice',
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
                    document.location.reload();
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: timer
                },function(){

                });
            });
        }
    });

    $('#btnSaveaddonShoopingCartModal').click(function(){
        if(formValidator('#addonShoopingCartForm')){
            var data = serialzeForm('#addonShoopingCartForm');
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/orders_v1/app/addon_shopping_info',
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
                    document.location.reload();
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: timer
                },function(){

                });
            });
        }
    });

    $('#btnSaveSaleModal').click(function(){
        if(formValidator('#saleForm')){
            var data = serialzeForm('#saleForm');
            swal({
                title: "Apakah anda yakin?",
                text: "Pesanan telah dibayar dan akan dilanjutkan ke tim logistik!!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
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
                        url: document.app.site_url+'/orders_v1/verify/sale',
                        data: data
                    })
                    .done(function( response ) {
                        $('.preloader').fadeOut();
                        var title = 'Berhasil!',
                            timer = 1000;

                        if(!response.status) {
                            var timer = 3000;
                            title = 'Gagal!';
                        }

                        swal({
                            title: title,
                            text: response.message,
                            timer: timer
                        },function(){
                            if(response.status)
                            {
                                window.location.href = document.app.site_url+'/'+document.app.penjualan.orders_state;
                            }
                        });
                    });
                }
            });
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

    $('#provinsi_id_select').on('change',function(){
        var provinsi_id = $(this).val(),
            provinsi = '';
        $( "#provinsi_id_select option:selected" ).each(function() {
            provinsi = $( this ).text();
        });
        formPopulate('#updateCustomerInfoForm', {
            provinsi_id,
            provinsi
        })
        $('#kabupaten_id_select').html('<option>Pilih</option>');

        $.ajax({
            method: "POST",
            url: document.app.site_url+'/wilayah/kabupaten/'+provinsi_id
        })
        .done(function( response ) {
            if(response.data){
                response.data.forEach(function(val, key){
                    var name = ucwords(val.name);
                    $('#kabupaten_id_select').append(`<option value="${val.id}">${name}</option>`)
                });
                formPopulate('#updateCustomerInfoForm', {
                    'kabupaten_id': document.app.penjualan.orders.customer_address.kabupaten_id
                });
                $('#kabupaten_id_select').change()
            }
        });
    });

    $('#kabupaten_id_select').on('change',function(){
        var kabupaten_id = $(this).val(),
            kabupaten = '';
        $( "#kabupaten_id_select option:selected" ).each(function() {
            kabupaten = $( this ).text();
        });
        formPopulate('#updateCustomerInfoForm', {
            kabupaten_id,
            kabupaten
        })
        $('#kecamatan_id_select').html('<option>Pilih</option>');

        $.ajax({
            method: "POST",
            url: document.app.site_url+'/wilayah/kecamatan/'+kabupaten_id
        })
        .done(function( response ) {
            if(response.data){
                response.data.forEach(function(val, key){
                    var name = ucwords(val.name);
                    $('#kecamatan_id_select').append(`<option value="${val.id}">${name}</option>`)
                });
                formPopulate('#updateCustomerInfoForm', {
                    'kecamatan_id': document.app.penjualan.orders.customer_address.kecamatan_id
                });
                $('#kecamatan_id_select').change()
            }
        });
    });

    $('#kecamatan_id_select').on('change',function(){
        var kecamatan_id = $(this).val(),
            kecamatan = '';
        $( "#kecamatan_id_select option:selected" ).each(function() {
            kecamatan = $( this ).text();
        });
        formPopulate('#updateCustomerInfoForm', {
            kecamatan_id,
            kecamatan
        })
        $('#desa_id_select').html('<option>Pilih</option>');

        $.ajax({
            method: "POST",
            url: document.app.site_url+'/wilayah/desa/'+kecamatan_id
        })
        .done(function( response ) {
            if(response.data){
                response.data.forEach(function(val, key){
                    console.log(val);
                    var name = ucwords(val.name);
                    $('#desa_id_select').append(`<option value="${val.id}" kode-pos="${val.kode_pos}">${name}</option>`)
                });
                formPopulate('#updateCustomerInfoForm', {
                    'desa_id': document.app.penjualan.orders.customer_address.desa_id
                });
                $('#desa_id_select').change()
            }
        });
    });

    $('#desa_id_select').on('change',function(){
        var desa_id = $(this).val(),
            desa_kelurahan = '',
            kode_pos = '';
        $( "#desa_id_select option:selected" ).each(function() {
            desa_kelurahan = $( this ).text();
            kode_pos = $(this).attr('kode-pos');
        });

        formPopulate('#updateCustomerInfoForm', {
            'desa_id': desa_id,
            'desa_kelurahan': desa_kelurahan
        })

        if(kode_pos != '')
        {
            formPopulate('#updateCustomerInfoForm', {
                'postal_code': kode_pos
            })
        }
    });

    $('#updateCustomerInfoForm [name=provinsi_id]').on('change', function(){
        var sl = $('#provinsi_id_select');
        initSelectOpt(this, sl);
    });
    $('#updateCustomerInfoForm [name=kabupaten_id]').on('change', function(){
        var sl = $('#kabupaten_id_select');
        initSelectOpt(this, sl);
    });
    $('#updateCustomerInfoForm [name=kecamatan_id]').on('change', function(){
        var sl = $('#kecamatan_id_select');
        initSelectOpt(this, sl);
    });
    $('#updateCustomerInfoForm [name=desa_id]').on('change', function(){
        var sl = $('#desa_id_select');
        initSelectOpt(this, sl);
    });

    $('#btnSaveCustomerModal').click(function(){
        var data = serialzeForm('#updateCustomerInfoForm');

        swal({
            title: "Apakah anda yakin?",
            text: "Anda akan mengubah data customer ini!",
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
                    url: document.app.site_url+'/orders_v1/app/update',
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
                        $('#updateCustomerInfoForm')[0].reset()
                        $('#updateCustomerModal').modal('toggle')
                    }

                    swal({
                        title: title,
                        text: response.message,
                        timer: timer
                    },function(){
                        document.location.reload()
                    });
                });
            }
        });
    });

    $('#btnSaveShopingCartModal').click(function(){
        var data = serialzeForm('#shopingCartForm');

        swal({
            title: "Apakah anda yakin?",
            text: "Anda akan mengubah data produk ini!",
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
                    url: document.app.site_url+'/orders_v1/app/update_shooping_info',
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
                        document.location.reload()
                    });
                });
            }
        });
    });
});


function initSelectOpt(ths, sl)
{
    if($(ths).val() != sl.val()){
        sl.val($(ths).val());
        console.log('change')
    } else console.log('not change')
}
