$(document).ready(function(){
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });

    var numberer_user = 1;
    userTable = $('#UserTable').on('preXhr.dt', function ( e, settings, data ){
            numberer_user = data.start + 1;
            data.role_id = $('#filterSection [name=role_id]').val();

            $('.row .white-box').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });
            return data;
        }).on('xhr.dt', function ( e, settings, json, xhr ){
            $('.row .white-box').unblock();
            if(!document.datatable_search_change_event)
            {
                $("div.dataTables_filter input").unbind();
                $("div.dataTables_filter input").keyup( function (e) {
                    if (e.keyCode == 13) {
                        userTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;
        }).DataTable({
            serverSide: true,
            bInfo: false,
            ajax: {
                url: document.app.site_url + '/user/get',
                type: 'POST'
            },
            columns: [
                {
                    name: 'Number',
                    width: "5%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        return numberer_user++;
                    }
                },
                { data: "username" },
                { data: "email" },
                { data: "first_name" },
                { data: "last_name" },
                {
                    data: "status",
                    render: function ( data, type, full, meta ) {
                        var text = '<span class="label label-danger">deactivated</span>';
                        if(data == 1) text = '<span class="label label-success">activated</span>';
                        return text;
                    }
                },
                {
                    data: 'user_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        //
                        if(document.app.access_list.sso_users_upd)
                        {
                            // edit
                            button.push('<button onclick="updUser('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                        }

                        if(document.app.access_list.sso_users_del)
                        {
                            // hapus
                            button.push('<button onclick="delUser('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                        }

                        if(document.app.access_list.sso_users_role_set)
                        {
                            // set access
                            button.push('<a href="'+document.app.site_url+'/user/role/index/'+data+'" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-list-ul"></i></a>');
                        }

                        if(full.is_admin_manajer) return '';
                        else return button.join('');
                    }
                }
            ]
        });


    // $('#userModal').on('hidden.bs.modal', function () {
    //     $(this).find('.switchery').click();
    // })
});


function addUser(){
    $('#userForm')[0].reset();
    formPopulate('#userForm', {
        user_id: 0
    })
    $('#userModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function updUser(id){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/user/get/byid/'+id
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        formPopulate('#userForm', response)
    });

    $('#userModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$('#btnSaveUserModal').click(function(e){
    if(formValidator('#userForm')){
        var data = serialzeForm('#userForm');
        $('.preloader').fadeIn();
        $.ajax({
            method: "POST",
            url: document.app.site_url+'/user/app/save',
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
                $('#userForm')[0].reset()
                userTable.ajax.reload()
                $('#userModal').modal('toggle')
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

function delUser(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus user ini!",
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
                url: document.app.site_url+'/user/del/index/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                userTable.ajax.reload()
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
