$(document).ready(function(){
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: 'yyyy-mm-dd'
    });

    statistikCs = Morris.Area({
        element: 'morris-area-chart',
        xkey: 'periode',
        parseTime: false,
        ykeys: ['total_fu','total_pending','total_cancel','total_confirm','total_verify','total_sale'],
        labels: ['Follow Up','Pending','Cancel','Confirm Buy','Verify Pay','Sale'],
        pointSize: 3,
        fillOpacity: 0,
        pointStrokeColors:['#00C3ED','#004471','#E80094','#FF7A01','#FED700','#73B700'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 1,
        hideHover: 'auto',
        lineColors: ['#00C3ED','#004471','#E80094','#FF7A01','#FED700','#73B700'],
        resize: true,
        data: []
    });

    $('#btnFilter').click();

    $('#statusSection [name=status]').change(function(){
        $('#btnFilter').click();
    })
})

function setCS_User_id_and_load(id, name)
{
    portal.cs_user_id = id;
    loadDataCS(name);
}

function loadDataCS(name){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/statistik/get/cs/'+portal.cs_user_id,
        data: {
            'start_date': $('#date-range [name=start]').val(),
            'end_date': $('#date-range [name=end]').val()
        }
    })
    .done(function( response ) {
        $('.preloader').fadeOut();

        var indexs = ['periode'], keys = [], labels = [], colors = [], data_parsed = [];

        $('#statusSection [name=status]:checked').each(function(){
            indexs.push($(this).attr('keys'));
            keys.push($(this).attr('keys'));
            labels.push($(this).attr('labels'));
            colors.push($(this).attr('colors'));
        });

        try {
            response.data.forEach(function(val, keys){
                var tmp = [];
                indexs.forEach(function(ind){
                    tmp[ind] = val[ind];
                })
                data_parsed.push(tmp)
            });
        }
        catch(err) {
            console.log(err.message);
            return '';
        }
        if(name){
            $('#fieldCsName').html(name);
        }

        statistikCs.options.pointStrokeColors = colors;
        statistikCs.options.lineColors = colors;
        statistikCs.options.ykeys = keys;
        statistikCs.options.labels = labels
        setStatistik(data_parsed);
        statistikCs.redraw()

    });
}

function setStatistik(data) {
    statistikCs.setData(data);
}
