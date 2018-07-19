$(document).ready(function(){
    $('#dataTable tbody .inv.cursor-pointer').click(function(){
        var text = $(this).text().trim(),
            parent = $(this).parent('tr'),
            transaction_type = $(parent).find('[name=transaction_type]').val();

        if(text == 'will generated')
        {
            $(this).text('');
            $(parent).find('[name=is_sales]').val('0')
        }
        else if(transaction_type != 'D' && text == '')
        {
            $(this).html('<code>will generated</code>');
            $(parent).find('[name=is_sales]').val('1')
        }
        else
        {
            alert('Hanya transaksi kredit yg bisa dibuatkan inv');
        }
    })

    prosesChecking();
});

function prosesChecking()
{
    checkingTransaction();
}

function prosesTransaction()
{
    processFirstReady();
}

function processFirstReady()
{
    var first_el = $('#dataTable tbody tr td.processed[status=READY]:first');
    $('.preloader').fadeIn();

    if($('#payment_method_id').val() == 0 || $('#payment_method_id').val() == '')
    {
        alert('Pilih bank terlebih dahulu');
        return;
    }
    if(first_el.length)
    {
        first_el.each(function(){
            var tr = $(this).parent('tr');
            var data = {
                parent_statement_id: $(tr).find('[name=parent_statement_id]').val(),
                transaction_type: $(tr).find('[name=transaction_type]').val(),
                transaction_date: $(tr).find('[name=transaction_date]').val(),
                transaction_amount: $(tr).find('[name=transaction_amount]').val(),
                note: $(tr).find('[name=note]').val(),
                is_sales: $(tr).find('[name=is_sales]').val(),
                payment_method_id: $('#payment_method_id').val()
            }

            $.ajax({
                method: "POST",
                url: document.app.site_url+'/statement/app/save',
                data: data
            })
            .done(function( response ) {
                // console.log(response);

                if(!response.status) {
                    // gagal
                    $(tr).find('td.processed').attr('status', 'FAILED').html('<code>FAILED</code>')
                } else {
                    // berhasil
                    $(tr).find('td.processed').attr('status', 'WAITING_CHECK').html('<code>WAITING_CHECK</code>')
                }
                processFirstReady();
            });
        });
    }
    else
    {
        $('.preloader').fadeOut();
        processDateChange();
    }
}

function processDateChange()
{
    var first_el = $('#dataTable tbody tr td.processed[status=DATE_CHANGE]:first');
    $('.preloader').fadeIn();

    if($('#payment_method_id').val() == 0 || $('#payment_method_id').val() == '')
    {
        alert('Pilih bank terlebih dahulu');
        return;
    }
    if(first_el.length)
    {
        first_el.each(function(){
            var tr = $(this).parent('tr');
            var data = {
                account_statement_id: $(tr).find('[name=account_statement_id]').val(),
                transaction_date: $(tr).find('[name=transaction_date]').val(),
            }

            $.ajax({
                method: "POST",
                url: document.app.site_url+'/statement/app/change_date',
                data: data
            })
            .done(function( response ) {
                // console.log(response);

                if(!response.status) {
                    // gagal
                    $(tr).find('td.processed').attr('status', 'FAILED').html('<code>FAILED</code>')
                } else {
                    // berhasil
                    $(tr).find('td.processed').attr('status', 'DONE').html('<code>DONE</code>')
                }
                processDateChange();
            });
        });
    }
    else
    {
        alert('Sudah diproses semua');
        checkingTransaction();
    }
}

function checkingTransaction()
{
    var first_el = $('#dataTable tbody tr td.processed[status=WAITING_CHECK]:first');
    $('.preloader').fadeIn();
    if(first_el.length)
    {
        first_el.each(function(){
            var tr = $(this).parent('tr');
            var data = {
                parent_statement_id: $(tr).find('[name=parent_statement_id]').val(),
                transaction_type: $(tr).find('[name=transaction_type]').val(),
                transaction_date: $(tr).find('[name=transaction_date]').val(),
                transaction_amount: $(tr).find('[name=transaction_amount]').val(),
                note: $(tr).find('[name=note]').val(),
                is_sales: $(tr).find('[name=is_sales]').val(),
                payment_method_id: $('#payment_method_id').val()
            }

            $.ajax({
                method: "POST",
                url: document.app.site_url+'/statement/app/check',
                data: data
            })
            .done(function( response ) {
                // console.log(response);

                // new data
                if(!response.data)
                {
                    $(tr).find('td.processed').attr('status', 'READY').html('<code>READY</code>')
                }
                else
                {
                    if(response.data.generated_invoice) $(tr).find('td.inv.cursor-pointer').html(response.data.generated_invoice);

                    if(
                        response.data.transaction_date &&
                        response.data.transaction_date != data.transaction_date
                    )
                    {
                        $(tr).find('[name=account_statement_id]').val(response.data.account_statement_id);
                        $(tr).find('td.processed').attr('status', 'DATE_CHANGE').html('<code>DATE_CHANGE</code>')
                    }
                    else
                    {
                        $(tr).find('td.processed').attr('status', 'DONE').html('<code>DONE</code>')
                    }

                }

                checkingTransaction();
            });
        });
    }
    else
    {
        $('.preloader').fadeOut();
    }
}
