$(document).ready(function(){
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });

    $('#postbackForm [name=event_id]').on('change', function(){
        var trigger = $('#postbackForm [name=event_id] option:selected').attr('data');
        if(trigger=='') trigger = 'none';
        $('#event-trigger').html(trigger)
    })

    $('#postbackForm code.catch').dblclick(function(){
        var parameter = $(this).html(),
            el = $('#postbackForm [name=link]');
        el.val(el.val()+parameter);
    });

    var numberer = 1;
    postbackTable = $('#postbackTable').on('preXhr.dt', function ( e, settings, data ){
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
                        postbackTable.search( this.value ).draw();
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
                url: document.app.site_url + '/network/postback/get/'+network.network_id,
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
                { data: "link", orderable: false },
                { data: "event_name", orderable: false },
                { data: "trigger", orderable: false },
                {
                    data: "status",
                    render: function ( data, type, full, meta ) {
                        var text = '<span class="label label-danger">deactivated</span>';
                        if(data == 1) text = '<span class="label label-success">activated</span>';
                        return text;
                    }
                },
                {
                    data: 'network_postback_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        //
                        if(document.app.access_list.management_network_postback_upd)
                        {
                            // edit
                            button.push('<button onclick="updNetwork('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                        }

                        if(document.app.access_list.management_network_postback_del)
                        {
                            // hapus
                            button.push('<button onclick="delPostback('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                        }

                        return button.join('');
                    }
                }
            ]
        });
});

function addPostback(){
    $('#postbackForm')[0].reset();
    formPopulate('#postbackForm', {
        'network_id': network.network_id,
        'network_postback_id': 0
    });
    $('#postbackModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function updNetwork(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/network/postback/get_byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#postbackForm', response)
    });

    $('#postbackModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$('#btnSavePostbackModal').click(function(e){
    if(formValidator('#postbackForm')){
        var data = serialzeForm('#postbackForm');

        $('.preloader').fadeIn();
        $.ajax({
            method: "POST",
            url: document.app.site_url+'/network/postback/save/',
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
                $('#postbackForm')[0].reset()
                postbackTable.ajax.reload()
                $('#postbackModal').modal('toggle')
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

function delPostback(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus postback ini!",
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
                url: document.app.site_url+'/network/postback/del/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                postbackTable.ajax.reload()
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
