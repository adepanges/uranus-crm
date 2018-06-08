$(document).ready(function(){
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: 'yyyy-mm-dd'
    });

    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/bank_statement/get/bca'
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        $('#sortableStatement tbody').empty();

        if(response.data && response.data.length > 1)
        {
            response.data.forEach(function(rec, key){
                var kredit = rupiah(0), debit = rupiah(0),
                    inv = rec.generated_invoice,
                    balance = rupiah(rec.balance),
                    numberer = key+1;

                if(rec.transaction_type == 'K') var kredit = rupiah(rec.transaction_amount);
                else var debit = rupiah(rec.transaction_amount);
                if(typeof inv == 'object') inv = '';

                

                var el_row = `<tr class="advance-table-row">
                    <td style="width: 10px;"></td>
                    <td style="width: 10px;"><i class="fa fa-arrows-v handle"></i></td>
                    <td style="width: 10px;">${numberer}</td>
                    <td>${rec.transaction_date}</td>
                    <td>${inv}</td>
                    <td>${debit}</td>
                    <td>${kredit}</td>
                    <td>${balance}</td>
                </tr>`;
                $('#sortableStatement tbody').append(el_row);
                initDragger();
            });
        }
    });
});

function initDragger(){
    var el = document.getElementById('sortableStatement');
    var dragger = tableDragger(el, {
      mode: 'row',
      dragHandler: '.handle',
      onlyBody: true,
      animation: 300
    });

    dragger.on('drop',function(from, to, el){
      console.log(from);
      console.log(to);
    });
}
