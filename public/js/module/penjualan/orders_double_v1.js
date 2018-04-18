$(document).ready(function(){

    document.datatable_search_change_event2 = false;

    var numberer = 1;
    doubleOrdersTable = $('#doubleOrdersTable').on('preXhr.dt', function ( e, settings, data ){
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
                $("#doubleOrdersTable_filter input").unbind();
                $("#doubleOrdersTable_filter input").keyup( function (e) {
                    if (e.keyCode == 13) {
                        doubleOrdersTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;
        }).DataTable({
            serverSide: true,
            ajax: {
                url: document.app.site_url + '/orders_v1/double/get',
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
                        return numberer++;
                    }
                },
                { data: "customer_name" },
                { data: "customer_telephone" },
                { data: "double_reason" },
                { data: "created_at" },
                {
                    data: 'orders_double_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        if(document.app.access_list.penjualan_orders_double_detail)
                        {
                            button.push(`<a href="${document.app.site_url}/orders_v1/double/detail/${data}" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>`);
                        }

                        if(document.app.access_list.penjualan_orders_to_trash || document.app.role_active.role_id == 6)
                        {
                            button.push(`<a onclick="trashDoubleOrders(${data})" class="btn btn-rounded btn-warning"><i class="icon-trash"></i></a>`);
                        }
                        return button.join('');
                    }
                }
            ]
        });

    var numbererFollowUp = 1;
    doubleOrderFollowsTable = $('#doubleOrderFollowsTable').on('preXhr.dt', function ( e, settings, data ){
            numbererFollowUp = data.start + 1;
            $('.row .white-box').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });
        }).on('xhr.dt', function ( e, settings, json, xhr ){
            $('.row .white-box').unblock();
            if(!document.datatable_search_change_event2)
            {
                $("#doubleOrderFollowsTable_filter input").unbind();
                $("#doubleOrderFollowsTable_filter input").keyup( function (e) {
                    if (e.keyCode == 13) {
                        doubleOrderFollowsTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;
        }).DataTable({
            serverSide: true,
            ajax: {
                url: document.app.site_url + '/orders_v1/double/get_follow_up',
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
                        return numbererFollowUp++;
                    }
                },
                { data: "customer_name" },
                { data: "customer_telephone" },
                { data: "double_reason" },
                { data: "created_at" },
                {
                    data: 'orders_double_id',
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        if(document.app.access_list.penjualan_orders_double_detail)
                        {
                            button.push(`<a href="${document.app.site_url}/orders_v1/double/detail/${data}" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>`);
                        }

                        if(document.app.access_list.penjualan_orders_to_trash || document.app.role_active.role_id == 6)
                        {
                            button.push(`<a onclick="trashDoubleOrders(${data})" class="btn btn-rounded btn-warning"><i class="icon-trash"></i></a>`);
                        }
                        return button.join('');
                    }
                }
            ]
        });
});

function trashDoubleOrders(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan membuang semua double orders didalamnya!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
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
                url: document.app.site_url+'/orders_v1/double/trash/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                var title = 'Berhasil!';
                if(!response.status){
                    title = 'Gagal!';
                } else {
                    doubleOrdersTable.ajax.reload()
                    doubleOrderFollowsTable.ajax.reload()
                }

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
