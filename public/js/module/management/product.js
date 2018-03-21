$(document).ready(function(){
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });

    var numberer = 1;
    productTable = $('#productTable').on('preXhr.dt', function ( e, settings, data ){
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
                        return numberer++;
                    }
                },
                { data: "code" },
                { data: "merk" },
                { data: "name" },
                {
                    data: "weight",
                    render: function ( data, type, full, meta ) {
                        return data+' g';
                    }
                },
                {
                    data: "price",
                    render: function ( data, type, full, meta ) {
                        return data;
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
                    data: 'product_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        //
                        // if(document.app.access_list.management_cs_team_upd)
                        if(true)
                        {
                            // edit
                            button.push('<button onclick="updProduct('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                        }

                        // if(document.app.access_list.management_cs_team_del)
                        if(true)
                        {
                            // hapus
                            button.push('<button onclick="delProduct('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                        }

                        return button.join('');
                    }
                }
            ]
        });
});

function addProduct(){
    $('#productForm')[0].reset();
    formPopulate('#productForm', {
        network_id: 0
    })
    $('#productModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function updProduct(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/product/get/byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#productForm', response)
    });

    $('#productModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$('#btnSaveProduct').click(function(e){
    if(formValidator('#productForm')){
        var data = serialzeForm('#productForm');

        console.log(data);

        $('.preloader').fadeIn();
        $.ajax({
            method: "POST",
            url: document.app.site_url+'/product/app/save',
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
                $('#productForm')[0].reset()
                productTable.ajax.reload()
                $('#productModal').modal('toggle')
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

function delProduct(id){
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
                url: document.app.site_url+'/product/del/index/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                productTable.ajax.reload()
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
