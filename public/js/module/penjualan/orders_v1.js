$(document).ready(function(){

    $('#logistics_checklist_bulk').click(function(){
        if($(this).is(':checked')){
            $('.logistics_checklist').prop('checked', true);
        } else {
            $('.logistics_checklist').prop('checked', false);
        }
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
            $('#logistics_checklist_bulk').prop('checked', false)

        }).DataTable({
            serverSide: true,
            ajax: {
                url: document.app.site_url + '/orders_v1/get',
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
                        try {
                            var row = JSON.parse(data);
                            return `<b>${row.full_name}</b>`;
                        }
                        catch(err) {
                            console.log(err.message);
                            return '';
                        }
                    }
                },
                {
                    data: "customer_info",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        try {
                            var row = JSON.parse(data);
                            return `<i class="${full.call_method_icon}"> ${row.telephone}</i>`;
                        }
                        catch(err) {
                            console.log(err.message);
                            return '';
                        }
                    }
                },
                {
                    data: 'customer_address',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        try {
                            var customer_address = JSON.parse(data);
                            return `<span style="font-size: 9px;">
                            ${customer_address.address}<br>Ds./Kel. ${customer_address.desa_kelurahan}<br>Kec. ${customer_address.kecamatan}<br>${customer_address.kabupaten}<br>Prov. ${customer_address.provinsi}<br>${customer_address.postal_code}
                            </span>`;
                        }
                        catch(err) {
                            console.log(err.message);
                            return '';
                        }
                    }
                },
                { data: "package_name", orderable: false,
                    render: function ( data, type, full, meta ) {
                        return `<b style="color: #0077B5;">${data}</b>`;
                    }
                },
                { data: "total_price", orderable: false,
                    render: function ( data, type, full, meta ) {
                        return `<b style="color: #FF2D55;">${rupiah(data)}</b>`;
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
                            button.push(`<a href="${document.app.site_url}/orders_v1/detail/index/${data}" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>`);
                        }

                        if(!document.app.conf_assigned_to_cs)
                        {
                            button.push(`<button onclick="followUp(${data})" type="button" class="btn btn-primary btn-outline btn-circle btn-sm m-r-5"><i class="mdi mdi-briefcase-upload"></i></button>`);
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

    var numbererListCS = 1;
    listCSTable = $('#listCSTable').on('preXhr.dt', function ( e, settings, data ){
            numbererListCS = data.start + 1;
            $('.row .white-box').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });
            data.team_cs_id = $('#filter_team_cs').val();
        }).on('xhr.dt', function ( e, settings, json, xhr ){
            $('.row .white-box').unblock();
            $("#listCSTable_filter").hide();
        }).DataTable({
            serverSide: true,
            ajax: {
                url: document.app.site_url + '/member_team_cs/get',
                type: 'POST'
            },
            language: {
                infoFiltered: ""
            },
            columns: [
                {
                    name: 'Number',
                    width: "5%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        return numbererListCS++;
                    }
                },
                { data: "username", orderable: false},

                {
                    data: 'user_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [], found = false;

                        $('#list_cs [name=user_id]').each(function(){
                            if(data == $(this).val()) found = true;
                        })

                        if(!found)
                        {
                            full = btoa(JSON.stringify(full));
                            button.push(`<button onclick="addCStoList('${full}')" class="btn btn-success btn-rounded"><i class="fa fa-plus"></i></button>`);
                        }

                        return button.join('');
                    }
                }
            ]
        });

    $('#list_cs').on('click','button.close',function(){
        $(this).parent('.white-box').remove();
    })

    $('#btnProsesAssignModal').click(function(){
        var user_id = [],
            order_id = [],
            type = $('#assignOrdersModal [name=type_assign]:checked').val(),
            total_orders = $('#assignOrdersModal [name=total_orders]').val();


        if($('#list_cs [name=user_id]').length == 0)
        {
            alert('Tambahkan CS yang akan di assign orders');
            return;
        } else {
            $('#list_cs [name=user_id]').each(function(){
                user_id.push($(this).val());
            })
        }

        if(total_orders > 200)
        {
            alert('Total orders yang diperbolehkan hanya 200, agar loading tidak terlalu lama');
        }

        if(type == 'selected' && $('#ordersTable .logistics_checklist:checked').length == 0)
        {
            $('#totalSelected').text($('#ordersTable .logistics_checklist:checked').length);
            alert('Belum ada orders yang dipilih');
            return;
        } else if(type == 'selected'){
            $('#ordersTable .logistics_checklist:checked').each(function(){
                order_id.push($(this).val());
            })
        }

        swal({
            title: "Apakah anda yakin?",
            text: "Anda akan assign ke masing masing cs yg dipilih!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Proses",
            cancelButtonText: "Batal",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $('.preloader').fadeIn();
                $.ajax({
                    method: "POST",
                    url: document.app.site_url+'/orders_v1/assign/orders/',
                    data: {
                        user_id: btoa(JSON.stringify(user_id)),
                        order_id: btoa(JSON.stringify(order_id)),
                        type: type,
                        total_orders: total_orders
                    }
                })
                .done(function( response ) {
                    $('.preloader').fadeOut();

                    ordersTable.ajax.reload()

                    var title = 'Berhasil!';
                    if(!response.status) title = 'Gagal!';
                    else assignOrders();

                    swal({
                        title: title,
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: true
                    });
                });
            }
        });
    });
});

function assignOrders(){
    var count = $('#count_new_order').html(),
        count_selected = $('#ordersTable .logistics_checklist:checked').length;

    if(count > 200 ) count = 200;
    $('#assignOrdersModal [name=total_orders]').val(count)
    $('#totalSelected').text(count_selected);

    $('#list_cs').empty();

    $('#assignOrdersModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}
function addCStoList(data){
    data = JSON.parse(atob(data));
    var el = `<div class="col-md-4 col-md-6 col-md-xs-12 white-box">
        <input type="hidden" name="user_id" value="${data.user_id}">
        ${data.username}
        <button type="button" class="close" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>`;
    $('#list_cs').append(el);
    listCSTable.ajax.reload();
}

function findCS(){
    listCSTable.ajax.reload();
    $('#findCSModal').modal({
        backdrop: 'static',
        keyboard: false
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
