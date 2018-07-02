$(document).ready(function(){
    jQuery('#date-range').datepicker({
        toggleActive: true,
        format: 'yyyy-mm-dd'
    });

    loadBankStatement();
});

function loadBankStatement(){
    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/bank_statement/get/'+document.app.uri_bank,
        data: {
            date_start: $('#date-range [name=start]').val(),
            date_end: $('#date-range [name=end]').val()
        }
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        $('#sortableStatement tbody').empty();

        document.app.balance_before = parseInt(response.balance_before);
        document.app.sequence_before = parseInt(response.sequence_before);

        if(response.message && response.message != '') alert(response.message);
        generateTableTransaction(response.data, response.balance_before, response.sequence_before);

    });
}

function generateTableTransaction(data_transaction, expected_balance, expected_sequence)
{
    expected_balance = parseInt(expected_balance);
    expected_sequence = parseInt(expected_sequence);

    if(data_transaction && data_transaction.length > 1)
    {
        data_transaction.forEach(function(rec, key){
            var kredit = '', debit = '',
                inv = rec.generated_invoice,
                balance = parseInt(rec.balance),
                balance_rupiah = rupiah(rec.balance),
                transaction_amount = parseInt(rec.transaction_amount),
                numberer = key + 1;

            // find expected_balance
            if(rec.transaction_type == 'K') {
                var kredit = rupiah(transaction_amount);
                expected_balance += transaction_amount;
            }
            else if(rec.transaction_type == 'D')
            {
                var debit = rupiah(transaction_amount);
                expected_balance -= transaction_amount;
            }

            // find expected_sequence
            if(expected_sequence == null && rec.account_statement_seq != 0) expected_sequence = parseInt(rec.account_statement_seq);
            else expected_sequence++;

            if(typeof inv == 'object') inv = '';
            var tooltip = '';
            if(rec.note != ''){
                tooltip = `<span class="mytooltip tooltip-effect-5">
                <span class="tooltip-item">info</span> <span class="tooltip-content clearfix">
                  <span class="tooltip-text">${rec.note}</span> </span>
                </span>`;
            }

            // flagging balance error
            var class_trx_error = '',
                expected_balance_html = '';
            if(isNaN(balance) || expected_balance != balance)
            {
                class_trx_error = 'table-row-danger';
                expected_balance_html = '<span class="font-color-green pull-right">'+rupiah(expected_balance)+'</span>'
            }

            // flagging sequence error
            if(expected_sequence != rec.account_statement_seq)
            {
                class_trx_error = 'table-row-warning';
            }

            var el_row = `<tr class="${class_trx_error}"
                    data-account-statement-id="${rec.account_statement_id}"
                    data-account-statement-seq="${rec.account_statement_seq}">
                <td style="width: 10px;"></td>
                <td style="width: 10px;"><i class="fa fa-arrows-v handle"></i></td>
                <td style="width: 10px;">${numberer}</td>
                <td style="width: 120px;">${rec.transaction_date}</td>
                <td style="width: 300px;">${inv}</td>
                <td style="width: 50px;">${tooltip}</td>
                <td class="font-color-red">${debit}</td>
                <td class="font-color-green">${kredit}</td>
                <td>${balance_rupiah} ${expected_balance_html}</td>
            </tr>`;
            $('#sortableStatement tbody').append(el_row);
        });
        initDragger();
    }
}

function initDragger()
{
    var el = document.getElementById('sortableStatement');
    var dragger = tableDragger(el,
    {
        mode: 'row',
        dragHandler: '.handle',
        onlyBody: true,
        animation: 300
    });

    dragger.on('drop', function(from, to, el)
    {
        var target_account_statement_seq = document.app.sequence_before + to,
            el_index = to - 1,
            target_element = $('#sortableStatement tbody tr:eq(' + el_index + ')'),
            current_account_statement_seq = $(target_element).attr('data-account-statement-seq'),
            account_statement_id = $(target_element).attr('data-account-statement-id');

            $('.preloader').fadeIn();
            $.ajax({
                method: "POST",
                url: document.app.site_url+'/bank_statement/app/upd_sequence',
                data: {
                    account_statement_id,
                    current_account_statement_seq,
                    target_account_statement_seq
                }
            })
            .done(function( response ) {
                $('.preloader').fadeOut();
                loadBankStatement();
                alert(response.message);
            });
    });
}

function fixBalance(payment_method_id)
{
    var transaction = [];

    if($('#sortableStatement tbody tr.table-row-warning').length > 0){
        alert('Urutan belum berurutan, fix sequence terlebih dahulu');
        return;
    } else {
        $('#sortableStatement tbody tr.table-row-danger').each(function(){
            var current_id = parseInt($(this).attr('data-account-statement-id'));
            transaction.push({
                id: current_id
            });
        })
    }

    if($('#sortableStatement tbody tr.table-row-danger').length == 0)
    {
        alert('Balance sudah fix');
        return;
    }

    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/bank_statement/app/fix_balance/'+payment_method_id,
        data: {
            transaction: JSON.stringify(transaction)
        }
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        loadBankStatement();
        alert(response.message);
    });
}

function fixSequence(payment_method_id){
    var transaction = [];
    $('#sortableStatement tbody tr.table-row-warning').each(function(){
        var current_seq = parseInt($(this).attr('data-account-statement-seq')),
            current_id = parseInt($(this).attr('data-account-statement-id')),
            index_element = $('#sortableStatement tbody tr').index(this),
            target_seq = document.app.sequence_before + (index_element + 1);

        transaction.push({
            id: current_id,
            seq: current_seq,
            target_seq: target_seq
        });
    });

    if($('#sortableStatement tbody tr.table-row-warning').length == 0)
    {
        alert('Sequence sudah fix');
        return;
    }

    $('.preloader').fadeIn();
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/bank_statement/app/fix_sequence/'+payment_method_id,
        data: {
            transaction: JSON.stringify(transaction)
        }
    })
    .done(function( response ) {
        $('.preloader').fadeOut();
        loadBankStatement();
        alert(response.message);
    });
}
