$(document).ready(function(){

    var numberer = 1;
    ordersTable = $('#ordersTable').on('preXhr.dt', function ( e, settings, data ){
            numberer = data.start + 1;
            $('.row .white-box').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });
        }).on('xhr.dt', function ( e, settings, json, xhr ){
            $('.row .white-box').unblock();
            if(!document.datatable_search_change_event)
            {
                $("div.dataTables_filter input").unbind();
                $("div.dataTables_filter input").keyup( function (e) {
                    if (e.keyCode == 13) {
                        ordersTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;
        }).DataTable({
            serverSide: true,
            ajax: {
                url: document.app.site_url + '/packing_v1/get/index/pickup',
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
                { data: "created_at", orderable: false},
                { data: "order_code", orderable: false},
                {
                    data: 'customer_info',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var data = JSON.parse(data);
                        return data.full_name;
                    }
                },
                { data: "package_name", orderable: false },
                {
                    data: 'order_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        button.push(`<span class="label label-warning label-rouded">${full.logistic_name}</span>`)
                        return button.join('');
                    }
                },
                {
                    data: 'order_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        //
                        if(document.app.access_list.logistik_orders_detail)
                        {
                            button.push(`<a href="${document.app.site_url}/packing_v1/detail/index/${data}" type="button" class="btn btn-primary btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>`);
                        }

                        var part_data = btoa(JSON.stringify({
                            'order_id': full.order_id,
                            'logistic_id': full.logistic_id,
                            'shipping_code': full.shipping_code
                        }));
                        if(document.app.access_list.logistik_packing_action_shipping)
                        {
                            button.push(`<button onclick="onShipping('${part_data}')" type="button" class="btn btn-success btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-truck"></i></button>`);
                        }
                        return button.join('');
                    }
                }
            ]
        });
});

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
