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
                url: document.app.site_url + '/orders_v1/trash/get',
                type: 'POST'
            },
            language: {
                infoFiltered: ""
            },
            columns: [
                {
                    data: 'order_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        return `<input class="logistics_checklist" type="checkbox" value="${data}">`;
                    }
                },
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
                    data: "customer_info",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var row = JSON.parse(data);
                        return row.full_name;
                    }
                },
                {
                    data: "customer_info",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var row = JSON.parse(data);
                        return `<i class="${full.call_method_icon}"> ${row.telephone}</i>`;
                    }
                },
                { data: "package_name", orderable: false },
                { data: "total_price", orderable: false,
                    render: function ( data, type, full, meta ) {
                        return rupiah(data);
                    }
                },
                {
                    data: 'order_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];

                        if(full.orders_double_id)
                        {
                            button.push(`<span class="label label-warning label-rouded">double orders</span><br>`)
                        }
                        button.push(`<span class="label label-info label-rouded">${full.order_status}</span>`)

                        return button.join(' ');
                    }
                },
                {
                    data: 'order_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        //
                        if(document.app.access_list.penjualan_orders_detail)
                        {
                            button.push(`<a href="${document.app.site_url}/orders_v1/detail/index/${data}" type="button" class="btn btn-primary btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>`);
                        }

                        if(document.app.access_list.penjualan_orders_delete)
                        {
                            button.push(`<button onclick="deleteOrders(${data})" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-trash"></i></button>`);
                        }
                        return button.join('');
                    }
                }
            ]
        });
});

function deleteBulk(){
    var el = $('.logistics_checklist:checked'),
        orders = [],
        orders_base64 = '';

    if(el.length){
        el.each(function( index ) {
          orders.push($(this).val())
        });
        orders_base64 = btoa(orders.join(','));
        swal({
            title: "Apakah anda yakin?",
            text: "Pesanan akan dihapus permanen",
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
                    url: document.app.site_url+'/orders_v1/trash/del',
                    data: {
                        'order_id': orders_base64
                    }
                })
                .done(function( response ) {
                    $('.preloader').fadeOut();
                    var title = 'Berhasil!';
                    if(!response.status) title = 'Gagal!';
                    else ordersTable.ajax.reload()

                    swal({
                        title: title,
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: true
                    });
                });
            }
        });
    } else {
        alert('Check orders terlebih dahulu');
    }

}

function deleteOrders(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus orders ini!",
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
                url: document.app.site_url+'/orders_v1/trash/del',
                data: {
                    'order_id': btoa(id)
                }
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                ordersTable.ajax.reload()
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


$('#logistics_checklist_bulk').click(function(){
    if($(this).is(':checked')){
        $('.logistics_checklist').prop('checked', true);
    } else {
        $('.logistics_checklist').prop('checked', false);
    }
});
