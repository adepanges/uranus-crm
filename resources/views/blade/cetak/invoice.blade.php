<!DOCTYPE html>
<html lang="id"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Invoice</title>
</head>

<body style="font-family: open sans, tahoma, sans-serif; margin: 0; -webkit-print-color-adjust: exact" cz-shortcut-listen="true">

<div style="
    background: url({{ base_url('images/invoice/paid-stamp.png') }}) center no-repeat;
    background-size: contain;
    width: 790px;">

    <table width="790" cellspacing="0" cellpadding="0" class="container" style="width: 790px; padding: 20px;">
        <tbody><tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%; padding-bottom: 20px;">
                    <tbody>
                        <tr style="margin-top: 8px; margin-bottom: 8px;">
                            <td>
                                <img src="{{ base_url('images/logo/dermeva_logo_205x41.png') }}" alt="Dermeva">
                            </td>
                            <td style="text-align: right; padding-right: 15px;">
                                <a style="color: #42B549; font-size: 14px; text-decoration: none;" href="javascript:window.print()">
                                    <span style="vertical-align: middle">Cetak</span>
                                    <img src="{{ base_url('images/invoice/print.png') }}" alt="Print" style="vertical-align: middle;">
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%; padding-bottom: 20px;">
                    <tbody>
                        <tr style="font-size: 20px; font-weight: 600;">
                            <td style="padding-bottom: 5px;">
                                <span>Invoice</span>
                            </td>
                        </tr>
                        <tr style="font-size: 13px;">
                            <td style="padding-bottom: 5px;">
                                <span>Diterbitkan atas nama:</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right: 10px;">
                                <table style="width: 100%; border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0">
                                    <tbody><tr style="font-size: 13px;">
                                        <td>
                                            <table style="width: 100%; border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 80px; font-weight: 600; padding: 3px 20px 3px 0;" width="80">Penjual</td>
                                                        <td style="padding: 3px 0;">Dermeva Kosmetik Indonesia</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 13px;">
                                        <td>
                                            <table style="width: 100%; border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 80px; font-weight: 600; padding: 3px 20px 3px 0;" width="80">Nomor</td>
                                                        <td style="padding: 3px 0;">{{ $invoice->invoice_number }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 13px;">
                                        <td>
                                            <table style="width: 100%; border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 80px; font-weight: 600; padding: 3px 20px 3px 0;" width="80">Tanggal</td>
                                                        <td style="padding: 3px 0;">{{ tanggal_indonesia(date('Y-m-d', strtotime($invoice->publish_date))) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                </tbody></table>
                            </td>
                            <td style="padding-left: 10px;vertical-align: text-top;">
                                <table style="width: 100%; border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0">
                                    <tbody><tr style="font-size: 13px;">
                                        <td>
                                            <table style="width: 100%; border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                    <tr>

                                                        <td style="width: 80px;vertical-align: text-top; font-weight: 600; padding: 3px 20px 3px 0;" width="80">Pembayaran</td>

                                                        <td>


                                                            <div style="padding-bottom: 3px;"> {{ $invoice->payment_method }} &nbsp;

                                                            </div>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                </tbody></table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                    $package_name = '';
                    $package_price = '';
                ?>

                @foreach ($invoice->order_cart as $key_cart => $value_cart)
                <?php
                    $package_name = $value_cart->package_name;
                    $package_price = $value_cart->package_price;
                ?>
                @endforeach

                <table style="width: 100%; text-align: center; border-top: 1px solid rgba(0,0,0,0.1); border-bottom: 1px solid rgba(0,0,0,0.1); padding: 15px 0;" width="100%" cellspacing="0" cellpadding="0">
                    <thead style="font-size: 14px;">
                        <tr><th style="font-weight: 600; text-align: left; padding: 0 5px 15px 15px;">Nama Produk</th>
                        <th style="width: 120px; font-weight: 600; padding: 0 5px 15px;" width="120">Jumlah Barang</th>
                        <th style="width: 115px; font-weight: 600; padding: 0 5px 15px;" width="115">Harga Barang</th>
                        <th style="width: 115px; font-weight: 600; text-align: right; padding: 0 30px 15px 5px;" width="115">Subtotal</th>
                    </tr></thead>
                    <tbody>

                        @foreach ($invoice->order_cart as $key_cart => $value_cart)
                        <tr style="font-size: 13px;">
                            <td style="text-align: left; padding: 8px 5px 8px 15px;">{{ $value_cart->product_name }}</td>
                            <td style="width: 120px; padding: 8px 5px;" width="120">{{ $value_cart->qty }}</td>
                            <td style="width: 115px; padding: 8px 5px;" width="115"></td>
                            <td style="width: 115px; text-align: right; padding: 8px 30px 8px 5px;" width="115"></td>
                        </tr>
                        @endforeach

                        <tr style="font-size: 13px; background-color: rgba(0,0,0,0.1);" bgcolor="#F1F1F1">
                            <td colspan="3" style="font-weight: 600; text-align: left; padding: 8px 5px 8px 15px;">{{ $package_name }}</td>
                            <td style="width: 115px; font-weight: 600; text-align: right; padding: 8px 30px 8px 5px;" width="115">{{ rupiah($package_price) }}</td>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%; padding: 0 0 20px;">
                    <tbody>
                        <tr>
                            <td width="35%" valign="top" style="width: 35%; vertical-align: top; padding-right: 5px;"></td>
                            <td width="65%" valign="top" style="width: 65%; vertical-align: top; padding-left: 5px;">
                                <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%; border-collapse: collapse;">
                                    <tbody><tr bgcolor="#F1F1F1" style="font-size: 15px; color: #42B549; background-color: rgba(0,0,0,0.1);">
                                        <td style="padding: 15px 0 15px 30px; font-weight: 600;">Total</td>
                                        <td style="padding: 15px 30px 15px 0; font-weight: 600; text-align: right; ">{{ rupiah($package_price) }}</td>
                                    </tr>
                                </tbody></table>
                            </td>
                        </tr>
                        <tr>
                            <td width="35%" valign="top" style="width: 35%; vertical-align: top; padding-right: 5px;"></td>
                            <td width="65%" valign="top" style="width: 65%; vertical-align: top; padding-left: 5px;">
                                <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%; border-collapse: collapse;">
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table width="100%" cellspacing="0" cellpadding="0" style="width: 100%; border-top: 1px dashed #DDD; padding: 25px 0px;">
                    <thead>
                        <tr><th style="text-align: left; padding: 0;">
                            <h3 style="font-size: 15px; font-weight: 600; margin: 0;">Tujuan Pengiriman</h3>
                        </th>

                    </tr></thead>
                    <tbody>
                        <tr>
                            <td style="font-size: 13px; line-height: 20px; padding: 10px 0;">
                                <p style="font-size: 14px; font-weight: 600; padding-bottom: 5px; margin: 0;">{{ $invoice->customer->full_name }}</p>
                                {{ $invoice->customer->telephone }}<br><br>
                                {{ $invoice->customer_address->address }}<br>
                                Ds./Kel. {{ $invoice->customer_address->desa_kelurahan }} Kec. {{ $invoice->customer_address->kecamatan }}<br>
                                {{ $invoice->customer_address->kabupaten }} Prov. {{ $invoice->customer_address->provinsi }}<br>
                                {{ $invoice->customer_address->postal_code }}<br>
                            </td>
                            <td style="font-size: 13px; line-height: 20px; padding: 10px 0;" width="50%" valign="top">

                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody></table>
    </div>



</body></html>
