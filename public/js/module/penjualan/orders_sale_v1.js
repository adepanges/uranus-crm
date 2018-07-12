$(document).ready(function(){
    $('#penjualan_checklist_bulk').click(function(){
        if($(this).is(':checked')){
            $('.penjualan_checklist').prop('checked', true);
        } else {
            $('.penjualan_checklist').prop('checked', false);
        }
    });

    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: 'yyyy-mm-dd'
    });

    var numberer = 1;
    ordersTable = $('#ordersTable').on('preXhr.dt', function ( e, settings, data ){
            numberer = data.start + 1;
            $('.row .white-box').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });

            data.date_start = $('#date-range [name=start]').val();
            data.date_end = $('#date-range [name=end]').val();
            data.filter_sale  = $('#filterSection [name=filter_sale]').val();
            data.filter_cs_id = $('#filterSection [name=filter_cs_id]').val();

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
            $('#count_sale').html(json.recordsFiltered);

        }).DataTable({
            serverSide: true,
            ajax: {
                url: document.app.site_url + '/orders_v1/get/index/sale',
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
                        return `<input class="penjualan_checklist" type="checkbox" value="${data}">`;
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
                {
                    data: "created_at", orderable: false,
                    render: function ( data, type, full, meta ) {
                        var data = data.split(' ');
                        return data[0];
                    }
                },
                {
                    data: "sale_date", orderable: false,
                    render: function ( data, type, full, meta ) {
                        var data = data.split(' ');
                        return data[0];
                    }
                },
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

                        if(full.cs_sale)
                        {
                            button.push(`<span class="label label-success label-rouded">CS: ${full.cs_sale}</span><br>`)
                        }

                        if(document.app.access_list.penjualan_orders_view_modifier)
                        {
                            button.push(`<span class="label label-warning label-rouded">FIN: ${full.username}</span><br>`)
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

                        if(document.app.access_list.penjualan_orders_to_trash)
                        {
                            button.push(`<button onclick="trashOrders(${data})" type="button" class="btn btn-warning btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-trash"></i></button>`);
                        }

                        return button.join('');
                    }
                }
            ]
        });
});

function trashOrders(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan membuang orders ini!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Buang",
        cancelButtonText: "Batal",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/orders_v1/app/trash/'+id
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

function cetakExcel(){
    var el = $('.penjualan_checklist:checked'),
        orders = [],
        orders_base64 = '';

    if(el.length){
        el.each(function( index ) {
          orders.push($(this).val())
        });
        orders_base64 = btoa(orders.join(','));
        window.open(document.app.site_url + '/orders_v1/cetak/excel/' + orders_base64);
    } else {
        alert('Check orders terlebih dahulu');
    }
}
