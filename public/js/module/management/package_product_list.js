$(document).ready(function(){
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });

    var numberer = 1;
    productListTable = $('#productListTable').on('preXhr.dt', function ( e, settings, data ){
            numberer = data.start + 1;
            $('#productListTable').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });
        }).on('xhr.dt', function ( e, settings, json, xhr ){
            $('#productListTable').unblock();
            if(!document.datatable_search_change_event)
            {
                $("div.dataTables_filter input[aria-controls=productListTable]").unbind();
                $("div.dataTables_filter input[aria-controls=productListTable]").keyup( function (e) {
                    if (e.keyCode == 13) {
                        productListTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;

            var total_price = 0;
            if(package_.price_type == 'RETAIL'){
                json.data.forEach(function(val, key){
                    if(val.status == 1){
                        total_price += (val.price * 1)
                    }
                });

                $('#totalPrice').html(rupiah(total_price));
            }

        }).DataTable({
            language: {
                infoFiltered: ""
            },
            serverSide: true,
            bInfo: false,
            ajax: {
                url: document.app.site_url + '/package/product_list/get/'+package_.product_package_id,
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
                { data: "merk" },
                { data: "name" },
                { data: "qty" },
                {
                    data: "price",
                    render: function ( data, type, full, meta ) {
                        if(!data) data = 0;
                        if(package_.price_type == 'RETAIL') return rupiah(data);
                        else return 'Package';
                    }
                },
                {
                    data: "status",
                    render: function ( data, type, full, meta ) {
                        var text = '<span class="label label-danger">deactivated</span>';
                        if(data == 1) text = '<span class="label label-success">activated</span>';
                        return text;
                    }
                },
                {
                    data: 'product_package_list_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        //
                        if(document.app.access_list.management_package_product_manage)
                        {
                            // edit
                            button.push('<button onclick="updNetwork('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                        }

                        if(document.app.access_list.management_package_product_manage)
                        {
                            // hapus
                            button.push('<button onclick="delProductList('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                        }

                        button.push('<input type="hidden" class="product_id_added" value="'+full.product_id+'">');

                        return button.join('');
                    }
                }
            ]
        });

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
                        //
                        if(document.app.access_list.management_product_upd)
                        {
                            // edit
                            button.push('<button onclick=addList("'+btoa(JSON.stringify(full))+'") type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-plus"></i></button>');
                        }

                        if($('.product_id_added').length > 0){

                            $('.product_id_added').each(function() {
                                if($( this ).val() == data) button = ['<span class="label label-success">added</span>'];
                            });
                        }

                        if(full.status != 1) button = ['<span class="label label-danger">deactivated</span>'];

                        return button.join('');
                    }
                }
            ]
        });

    $('#btnSaveProductList').click(function(e){
        if(formValidator('#productListForm')){
            var data = serialzeForm('#productListForm');
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/package/product_list/save/',
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
                    $('#productListForm')[0].reset()
                    productListTable.ajax.reload()
                    $('#productListModal').modal('toggle')
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

    $('#btnAddProductList').click(function(e){
        var el = $('#addProductBulk .product_id_added');

        if(el.length > 0){
            var bulk = [];
            el.each(function(){
                bulk.push($(this).attr('data'));
            });

            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/package/product_list/save/',
                data: {
                    product_package_id: package_.product_package_id,
                    bulk: bulk
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
                    productListTable.ajax.reload()
                    $('#addProductListModal').modal('toggle')
                }

                swal({
                    title: title,
                    text: response.message,
                    timer: timer,
                    showConfirmButton: showConfirmButton
                });
            });
        } else {
            alert('list masih kosong');
        }
    })
});

function addProductList(){
    $('#addProductBulk').html('')
    productTable.ajax.reload();
    $('#addProductListModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function addList(json){
    var data = JSON.parse(atob(json));
    $('#addProductBulk').append(`<div class="well well-sm">
        <input type="hidden" data="${json}" class="product_id_added" value="${data.product_id}">
        <h4><b>${data.code} - ${data.merk} - ${data.name}</b></h4>
        <p>Harga: `+rupiah(data.price)+`</p>
    </div>`);
    productTable.ajax.reload();
}

function updNetwork(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/package/product_list/get_byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#productListForm', response)
    });

    $('#productListModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function delProductList(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan Product list ini!",
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
                url: document.app.site_url+'/package/product_list/del/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                productListTable.ajax.reload()
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
