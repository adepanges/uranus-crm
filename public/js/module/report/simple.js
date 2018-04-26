$(document).ready(function(){
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: 'yyyy-mm-dd'
    });


    var numberer = 1;
    reportTable = $('#reportTable').on('preXhr.dt', function ( e, settings, data ){
            numberer = data.start + 1;
            $('.row .white-box').block({
                message: '<h3>Please Wait...</h3>',
                css: {
                    border: '1px solid #fff'
                }
            });

            data.date_start = $('#date-range [name=start]').val();
            data.date_end = $('#date-range [name=end]').val();
            data.by_date = $('#filterSection [name=by_date]:checked').val();

        }).on('xhr.dt', function ( e, settings, json, xhr ){
            $('.row .white-box').unblock();
            if(!document.datatable_search_change_event)
            {
                $("div.dataTables_filter input").unbind();
                $("div.dataTables_filter input").keyup( function (e) {
                    if (e.keyCode == 13) {
                        reportTable.search( this.value ).draw();
                    }
                });
            }
            document.datatable_search_change_event = true;

            $('#fieldPenjualan').html(rupiah(json.information.total_price));
            $('#fieldSales').html(json.information.total_sale);
            $('#fieldProduct').html(json.information.product_total);
        }).DataTable({
            language: {
                infoFiltered: ""
            },
            serverSide: true,
            bInfo: false,
            ajax: {
                url: document.app.site_url + '/simple/get',
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
                { data: "name" },
                {
                    data: "total_penjualan",
                    render: function ( data, type, full, meta ) {
                        if(!data) data = 0;
                        return '<span style="color: #090;"><b>'+rupiah(data)+'</b></span>';
                    }
                },
                {
                    data: "total_product",
                    render: function ( data, type, full, meta ) {
                        if(!data) data = 0;
                        return data;
                    }
                },
                { data: "total_follow_up", width: "8%" },
                { data: "total_pending", width: "8%",
                    render: function ( data, type, full, meta ) {
                        return '<span style="color: #900;"><b>'+data+'</b></span>';
                    }
                },
                {
                    data: "total_cancel", width: "8%",
                    render: function ( data, type, full, meta ) {
                        return '<span style="color: #900;"><b>'+data+'</b></span>';
                    }
                },
                { data: "total_confirm_buy", width: "8%" },
                { data: "total_verify", width: "8%" },
                { data: "total_sale", width: "8%" },
                {
                    data: "name", width: "8%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var rate = (full.total_sale / full.total_follow_up);
                        if(isNaN(rate)) rate = 0;
                        rate = precisionRound((rate * 100), 2);
                        return '<span style="color: #900;"><b>'+rate+' %</b></span>';
                    }
                },
                {
                    data: "name", width: "8%",
                    orderable: false,
                    render: function ( data, type, full, meta ) {
                        var rate = (
                            (
                                parseInt(full.total_pending) + parseInt(full.total_confirm_buy) + parseInt(full.total_verify)
                            ) /
                            (
                                parseInt(full.total_follow_up) - parseInt(full.total_cancel)
                            )
                        );
                        if(isNaN(rate)) rate = 0;
                        rate = precisionRound((rate * 100), 2);
                        return '<span style="color: #900;"><b>'+rate+' %</b></span>';
                    }
                }
            ]
        });
});
