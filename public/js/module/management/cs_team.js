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

                        return button.join('');
                    }
                }
            ]
        });

});

function addUser(){
    $('#csTeamForm')[0].reset();
    $('#csTeamModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}
