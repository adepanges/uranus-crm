$(document).ready(function(){
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    var numberer = 1;
    dataTable = $('#dataTable').on('preXhr.dt', function ( e, settings, data ){
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
                        dataTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;
        }).DataTable({
            language: {
                infoFiltered: ""
            },
            serverSide: true,
            bInfo: false,
            ajax: {
                url: document.app.site_url + '/customer/get',
                type: 'POST'
            },
            aaSorting: [[1, 'asc']],
            columns: [
                {
                    name: 'Number',
                    width: "5%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        return numberer++;
                    }
                },
                { data: "full_name" },
                { data: "telephone" },
                { data: "email" },
                { data: "gender" },
                { data: "city" },
                {
                    data: 'customer_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];

                        button.push('<a href="'+document.app.site_url + '/customer/detail/index/' + data + '" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-eye"></i></a>');
                        
                        if(document.app.access_list.crm_customer_upd)
                        {
                            // edit
                            button.push('<button onclick="upd('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                        }

                        if(document.app.access_list.crm_customer_del)
                        {
                            // hapus
                            button.push('<button onclick="del('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                        }

                        return button.join('');
                    }
                }
            ]
        });

    $('#btnSaveApp').click(function(e){
        if(formValidator('#appForm')){
            var data = serialzeForm('#appForm');

            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/customer/app/save',
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
                    $('#appForm')[0].reset()
                    dataTable.ajax.reload()
                    $('#appModal').modal('toggle')
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
});

function add(){
    $('#appForm')[0].reset();
    formPopulate('#appForm', {
        customer_id: 0
    })
    $('#appModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function upd(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/customer/get/byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#appForm', response)
    });

    $('#appModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}


function del(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus customer ini!",
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
                url: document.app.site_url+'/customer/del/index/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                dataTable.ajax.reload()
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
