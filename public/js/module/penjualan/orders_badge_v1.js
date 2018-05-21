$(document).ready(function(){
    loadBadgePenjualan();
    setInterval(loadBadgePenjualan, document.app.penjualan.interval_badge_load);
});

function loadBadgePenjualan(){
    $.ajax({
        method: "POST",
        url: document.app.site_url+'/orders_v1/get/badge'
    })
    .done(function( response ) {
        console.log(`bagde load time ${response.system_process_time}`);
        $('#count_new_order').html(response.count_new);
        $('#count_double').html(response.count_double);
        $('#count_pending').html(response.count_pending);
        $('#count_confirm_buy').html(response.count_confirm_buy);
        $('#count_verify_pay').html(response.count_verify);
        $('#count_sale').html(response.count_sale);
        $('#count_cancel').html(response.count_cancel);
        $('#count_trash').html(response.count_trash);
        $('#count_assigned_order').html(response.count_assigned);
    });
}
