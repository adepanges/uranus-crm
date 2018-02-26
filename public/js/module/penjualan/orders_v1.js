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
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian.json'
            },
            serverSide: true,
            bInfo: false,
            ajax: {
                url: document.app.site_url + '/orders_v1/get',
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
                { data: "total_price", orderable: false },
                {
                    data: 'order_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var info = [];
                        if(full.order_status_id == 1) info.push(`<span class="label label-danger">${full.order_status}</span>`);
                        return info.join('<br>');
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
                            // edit
                            data = btoa(JSON.stringify(full));
                            button.push('<button onclick=detailOrders("'+data+'") type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></button>');
                        }

                        return button.join('');
                    }
                }
            ]
        });
});

function detailOrders(data){
    var data_parsed = JSON.parse(atob(data)),
        customer_address = JSON.parse(data_parsed.customer_address);

    var address = `${customer_address.address} Desa/Kel. ${customer_address.desa_kelurahan} Kec. ${customer_address.kecamatan} Kab. ${customer_address.kabupaten} Prov. ${customer_address.provinsi} Kode Pos: ${customer_address.postal_code}`;

    $('#ordersForm')[0].reset();
    formPopulate('#ordersForm', JSON.parse(data_parsed.customer_info));
    formPopulate('#ordersForm', {
        address,
        payment_method: data_parsed.payment_method
    });

    $('#ordersModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}
