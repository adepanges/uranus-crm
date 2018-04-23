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
        padding-left: 5mm;
        padding-top: 3mm;
        padding-right: 5mm;
        width: 82mm;
        height: 40mm;
        float: left;
        font-size: 1em;
        font-size: 11px;
        border: 1px solid #000;
    }

    .side.label-pengiriman {
        font-size: 12px;
        border: 1mm dotted #000;
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

    <?php
    foreach ($invoices as $key => $value)
    {
        // $package = [];
        // $produk_qty = 0;
        // foreach ($value->order_cart as $key => $value_cart) {
        //     $package[] = $value_cart['info']->package_name;
        //
        //     foreach ($value_cart['cart'] as $key => $value_cart_detail) {
        //         if(!empty($value_cart_detail->product_id))
        //         {
        //             $produk_qty = $produk_qty + $value_cart_detail->qty;
        //         }
        //     }
        // }
        // $package = implode(", ", $package);
        // dd($package);
    }
    // exit;
    ?>
</div>

<div id="printArea">
    <body>
<?php $count = 0; ?>
@foreach ($invoices as $key => $value)
        <?php
        $count++;
        $package = [];
        $produk_qty = 0;
        foreach ($value->order_cart as $key => $value_cart) {
            $package[] = $value_cart['info']->package_name;

            foreach ($value_cart['cart'] as $key => $value_cart_detail) {
                if(!empty($value_cart_detail->product_id))
                {
                    $produk_qty = $produk_qty + $value_cart_detail->qty;
                }
            }
        }
        $package = implode(", ", $package);
        // dd($produk_qty);
        ?>
        <div class="side">
            <b>Label Pengiriman:</b><br>
            {{ $value->customer->full_name }}<br>
            {{ $value->customer->telephone }}
            <hr>
            Logistik: <b>{{ $value->logistic_name }}</b><br>
            Paket: <b>{{ $package }}</b>, Qty : <b>{{ $produk_qty }}</b>
            <hr>
            {{ $value->customer_address->address }}<br>
            Ds./Kel. {{ $value->customer_address->desa_kelurahan }} Kec. {{ $value->customer_address->kecamatan }}<br>
            {{ $value->customer_address->kabupaten }} Prov. {{ $value->customer_address->provinsi }} {{ $value->customer_address->postal_code }}
        </div>
        @if($count==14)
        <br>
        <div class="pagebreak"></div>
        <br>
        <?php $count = 0; ?>
        @endif
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
