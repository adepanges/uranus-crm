$(document).ready(function(){
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());
    });

    var numberer_user = 1;
    csTeamTable = $('#csTeamTable').on('preXhr.dt', function ( e, settings, data ){
            numberer_user = data.start + 1;
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
                        csTeamTable.search( this.value ).draw();
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
                url: document.app.site_url + '/cs_team/get',
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
                { data: "name" },
                { data: "franchise_name" },
                { data: "username" },
                {
                    data: "jumlah_cs",
                    render: function ( data, type, full, meta ) {
                        var parsed = parseInt(data);
                        return (isNaN(parsed)?0:parsed);
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
                    data: 'team_cs_id',
                    width: "12%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var button = [];
                        //
                        if(document.app.access_list.management_cs_team_upd)
                        {
                            // edit
                            button.push('<button onclick="updCSTeam('+data+')" type="button" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="ti-pencil-alt"></i></button>');
                        }

                        if(document.app.access_list.management_cs_team_del)
                        {
                            // hapus
                            button.push('<button onclick="delCSTeam('+data+')" type="button" class="btn btn-danger btn-outline btn-circle btn-sm m-r-5"><i class="icon-trash"></i></button>');
                        }

                        // if(document.app.access_list.sso_users_role_set)
                        // {
                        //     // set access
                        //     button.push('<a href="'+document.app.site_url+'/user/role/index/'+data+'" class="btn btn-info btn-outline btn-circle btn-sm m-r-5"><i class="fa fa-list-ul"></i></a>');
                        // }

                        return button.join('');
                    }
                }
            ]
        });


});

document.app._leader_tim_init = false;

function initLeaderTim(){
    if(!document.app._leader_tim_init){
        document.app._leader_tim_init = true;
        $('#leaderSelect').select2({
            ajax: {
                url: document.app.module_url.sso+'/user/get',
                method: 'POST',
                data: function (params) {
                    var query = {
                        search: {
                            value: params.term,
                            regex: false
                        },
                        type: 'public'
                    }
                    return query;
                },
                processResults: function (data) {
                    var res = [];
                    data.data.forEach(function(val, key){
                        res.push({
                            id: val.user_id,
                            text: val.username
                        });
                    });
                    return {
                        results: res
                    };
                }
            }
        });
    }
}

$('#csTeamModal').on('shown.bs.modal', function () {
  initLeaderTim();
})

function addCSTeam(){
    $('#csTeamForm')[0].reset();
    $('#csTeamModal').modal({
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
        formPopulate('#csTeamForm', response)
    });

    $('#csTeamModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

$('#btnSaveCsTeamModal').click(function(e){
    if(formValidator('#csTeamForm')){
        var data = serialzeForm('#csTeamForm');
        $('.preloader').fadeIn();
        $.ajax({
            method: "POST",
            url: document.app.site_url+'/cs_team/app/save',
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
                $('#csTeamForm')[0].reset()
                csTeamTable.ajax.reload()
                $('#csTeamModal').modal('toggle')
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
