<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reason_model extends Penjualan_Model {

    function get_cancel()
    {
        return ['Tidak jadi beli','Tidak ada respon','Tidak merasa pesan','Nomor palsu'];
    }

    function get_pending()
    {
        return ['Sudah di WhatsApp','Nomor WhatsApp tidak keluar','Tidak diangkat','Minta dihubungi lagi nanti'];
    }
}
