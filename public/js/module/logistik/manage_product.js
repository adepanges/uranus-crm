$(document).ready(function(){

    var numberer = 1;
    stockTable = $('#stockTable').on('preXhr.dt', function ( e, settings, data ){
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
                    stockTable.search( this.value ).draw();
                }
            });
        }
        document.datatable_search_change_event = true;
    }).DataTable({
        serverSide: true,
        ajax: {
            url: document.app.site_url + '/inventory/manage/get',
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
            { data: "arrived_at", orderable: false },
            { data: "amount", orderable: false },
            { data: "used", orderable: false },
            { data: "notes", orderable: false },
            { data: "username", orderable: false },
            {
                data: 'inventory_id',
                orderable: false,
                render: function ( data, type, full, meta ) {
                    var button = [];
                    if(document.app.access_list.logistik_orders_detail)
                    {
                        button.push(`<a href="${document.app.site_url}/packing_v1/detail/index/${data}" type="button" class="btn btn-primary btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>`);

                        button.push(`<button onclick="pickUp(${data})" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="mdi mdi-package-up"></i></button>`);
                    }
                    return button.join('');
                }
            }
        ]
    })


});

function add(){
    $('#dataForm')[0].reset();
    $('#formModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function updCSTeam(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/cs_team/get/byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#dataForm', response)
    });

    $('#formModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function delCSTeam(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus tim ini!",
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
                url: document.app.site_url+'/cs_team/del/index/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                csTeamTable.ajax.reload()
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
