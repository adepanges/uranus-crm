<style>
#printArea {
    display: none;
}

@media print {
    #noPrintArea {
        display: none;
    }
    #printArea {
       display: block;
    }

    body {
    	font-family: "Calibri", "Tahoma", "Candara", serif;
    	color: #000;
    }

    @page { size: 8.27in 11.69in;  margin: 0mm; }

    .side {
        margin: 5mm;
        padding: 5mm;
        width: 82mm;
        height: 125.5mm;
        border: 0.01mm dotted #000;
        float: left;
        font-size: 1em;
    }

    .side.label-pengiriman {
        font-size: 1em;
    }

    .side img {
        width: 205px;
        height: 41px;
    }

    .pagebreak { page-break-before: always; }

    .product-list {
        display: flex;
    }

    .product-list.detail {
        margin-left: 5mm;
    }

    .side.invoice .label-pengiriman {
        font-size: 11px;
    }
}
</style>
<div id="noPrintArea">
        <div class="example-print">Dermeva Kosmetik Indonesia</div>
</div>

<div id="printArea">
    <body>
@foreach ($invoices as $key => $value)
        <div class="side label-pengiriman">
            <img src="{{ base_url('images/logo/dermeva_logo_205x41.png') }}">
            <hr>
            <h3>Label Pengiriman:</h3>
            {{ $value->customer->full_name }}<br>
            {{ $value->customer->telephone }}<br><br>
            {{ $value->customer_address->address }}<br>
            Ds./Kel. {{ $value->customer_address->desa_kelurahan }} Kec. {{ $value->customer_address->kecamatan }}<br>
            {{ $value->customer_address->kabupaten }} Prov. {{ $value->customer_address->provinsi }}<br>
            {{ $value->customer_address->postal_code }}<br>
            <br>
            <br>
            <hr>

        </div>

@for ($i=0; $i <= 2; $i++)
        <div class="side invoice">
        <img src="{{ base_url('images/logo/dermeva_logo_205x41.png') }}">
        <hr>
        <h3>INVOICE</h3>
        Penjual: <b>Dermeva Kosmetik Indonesia</b><br>
        Nomor: {{ $value->invoice_number }}<br>
        Tanggal: {{ tanggal_indonesia(date('Y-m-d', strtotime($value->paid_date))) }}<br>
        Metode Pembayaran: {{ $value->payment_method }}<br>
        <hr>
    <?php
    $package_name = '';
    $price = '';
    ?>

    @foreach ($value->order_cart as $key_cart => $value_cart)
        <?php
        $package_name = $value_cart->package_name;
        $package_price = $value_cart->package_price;
        ?>
    @endforeach
        <div class="product-list">
            <div style="flex-grow: 8">{{ $package_name }}</div>
            <div style="flex-grow: 2">{{ rupiah($package_price) }}</div>
        </div>

    @foreach ($value->order_cart as $key_cart => $value_cart)
        <div class="product-list detail">
            <div style="flex-grow: 8">{{ $value_cart->product_name }}</div>
            <div style="flex-grow: 2">Qty. {{ $value_cart->qty }}</div>
        </div>
    @endforeach
        <hr>
        <div class="label-pengiriman">
            Tujuan Pengiriman:<br>
            {{ $value->customer->full_name }}<br>
            {{ $value->customer->telephone }}<br><br>
            {{ $value->customer_address->address }}<br>
            Ds./Kel. {{ $value->customer_address->desa_kelurahan }} Kec. {{ $value->customer_address->kecamatan }}<br>
            {{ $value->customer_address->kabupaten }} Prov. {{ $value->customer_address->provinsi }}<br>
            {{ $value->customer_address->postal_code }}<br>
        </div>
    </div>
@endfor
        <div class="pagebreak"></div>
@endforeach
    </body>
</div>

<script type="text/javascript">
    var callback = function(){
      window.print();
    };

    if (
        document.readyState === "complete" ||
        (document.readyState !== "loading" && !document.documentElement.doScroll)
    ) {
      callback();
    } else {
      document.addEventListener("DOMContentLoaded", callback);
    }
</script>
