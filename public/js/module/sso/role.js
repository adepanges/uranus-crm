$(document).ready(function(){
    var numberer = 1;
    roleTable = $('#RoleTable').on('preXhr.dt', function ( e, settings, data ){
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
                        roleTable.search( this.value ).draw();
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
                url: document.app.site_url + '/user/role/get/' + user_role.user_id,
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
                { data: "role_label" },
                {
                    data: "is_primary",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var text = '';
                        if(data == 1) text = '<i class="fa fa-star" style="color: #75EBB1; font-size: 20px;"></i>';
                        return text;
                    }
                },
                { data: "franchise_name" },
                { data: "created_at" },
                {
                    data: 'user_role_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];

                        if(document.app.access_list.sso_users_role_del)
                        {
                            // hapus
                            button.push('<button onclick="delRole('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                        }

                        if(full.is_primary == 0)
                        {
                            button.push('<button onclick="primaryRole('+full.user_id+','+full.role_id+')" type="button" class="btn btn-warning btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-star"></i></button>');
                        }

                        return button.join('');
                    }
                }
            ]
        });


    // $('#userModal').on('hidden.bs.modal', function () {
    //     $(this).find('.switchery').click();
    // })
});

function addRole(){
    $('#roleForm')[0].reset();
    formPopulate('#roleForm', {
        user_id: user_role.user_id
    });
    $('#roleModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$('#btnSaveRoleModal').click(function(e){
    if(formValidator('#roleForm')){
        var data = serialzeForm('#roleForm');
        $('.preloader').fadeIn();
        $.ajax({
            method: "POST",
            url: document.app.site_url+'/user/role/add',
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
                $('#roleForm')[0].reset()
                roleTable.ajax.reload()
                $('#roleModal').modal('toggle')
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

function delRole(id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menghapus role ini!",
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
                url: document.app.site_url+'/user/role/del/'+id
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                roleTable.ajax.reload()
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

function primaryRole(user_id, role_id){
    swal({
        title: "Are you sure?",
        text: "Anda akan menjadikan role ini primary!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
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
                url: document.app.site_url+'/user/role/set_primary',
                data: {
                    user_id: user_id,
                    role_id: role_id
                }
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                roleTable.ajax.reload()
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
