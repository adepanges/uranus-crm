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
        margin: 0mm;
        padding: 10mm;
        width: 82mm;
        height: 125.5mm;
        float: left;
        font-size: 1em;
    }

    .side.label-pengiriman {
        font-size: 12px;
        border-right: 1mm dotted #000;
    }

    .side.botl {
        border-top: 1mm dotted #000;
        border-right: 1mm dotted #000;
    }
    .side.botr {
        border-top: 1mm dotted #000;
    }

    .side img {
        width: 205px;
        height: 41px;
    }

    .pagebreak { page-break-before: always; }

    .product-list {
        display: flex;
        font-size: 10px;
    }

    .product-list .product-name {
        width: 60%;
    }

    .product-list .product-qty {
        width: 10%;
    }

    .product-list .product-price {
        width: 40%;
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
@if ($i==0)
    <div class="side invoice">
@elseif ($i == 1)
    <div class="side botl">
@elseif ($i == 2)
    <div class="side botr">
@endif
        <img src="{{ base_url('images/logo/dermeva_logo_205x41.png') }}">
        <hr>
        <h3>INVOICE</h3>
        Penjual: <b>Dermeva Kosmetik Indonesia</b><br>
        Nomor: {{ $value->invoice_number }}<br>
        Tanggal: {{ tanggal_indonesia(date('Y-m-d', strtotime($value->paid_date))) }}<br>
        Metode Pembayaran: {{ $value->payment_method }}<br>
        <hr>

        @foreach ($value->order_cart as $key_cart => $value_cart)
        <div class="product-list">
            <div class="product-name">{{ $value_cart['info']->package_name }}</div>
            <div class="product-qty"></div>
            @if($value_cart['info']->price_type == 'PACKAGE')
            <div class="product-price">{{ rupiah($value_cart['info']->package_price) }}</div>
            @endif
        </div>
            @foreach ($value_cart['cart'] as $key_detail => $value_cart_detail)
        <div class="product-list detail">
            <div class="product-name">{{ $value_cart_detail->product_name }}</div>
            <div class="product-qty">
                @if(!empty($value_cart_detail->product_id))
                {{ 'x '.$value_cart_detail->qty }}
                @endif
            </div>
            @if($value_cart_detail->price_type == 'RETAIL')
            <div class="product-price">{{ rupiah($value_cart_detail->price) }}</div>
            @endif
        </div>
            @endforeach
        @endforeach
        <hr>
        <div class="product-list detail">
            <div class="product-name">Total</div>
            <div class="product-qty"></div>
            <div class="product-price">{{ rupiah($value->total_price) }}</div>
        </div>
        <hr>
        <div class="label-pengiriman" style="font-size: 11px;">
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
