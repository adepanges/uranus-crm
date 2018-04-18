<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('dd')) {
    function dd()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
    }
}

if (!function_exists('d')) {
    function d()
    {
        $args = func_get_args();
        call_user_func_array('dump', $args);
    }
}

if (!function_exists('dump')) {
    function dump()
    {
        $args = func_get_args();
        echo '<pre>';
        if(is_array($args) && isset($args[0])) var_dump($args[0]);
        else var_dump($args);
        echo '</pre>';
    }
}

if (!function_exists('is_valid_md5')) {
    function is_valid_md5($md5 ='')
    {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }
}

if (!function_exists('random_string')) {
    function random_string($random_string_length = 5)
    {
        $string = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $random_string_length; $i++) {
             $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}


if (!function_exists('rupiah')) {
    function rupiah($number)
    {
        return "Rp. ".number_format( $number, 0 , '' , '.' ).",-";
    }
}

if (!function_exists('tanggal_indonesia')) {
    function tanggal_indonesia($tanggal)
    {
    	$bulan = array (1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
    	$split = explode('-', $tanggal);
    	return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
    }
}

if (!function_exists('bind_string')) {
    function bind_string($str, $vars)
    {
        foreach ($vars as $key => $value) {
            $str = str_replace("@$key", $value, $str);
        }
        return $str;
    }
}

if (!function_exists('normalize_msisdn')) {
    function normalize_msisdn($numb)
    {
        if(substr($numb, 0, 1) == '+')
        {
            $numb = substr($numb, 1);
        }
        if(substr($numb, 0, 2) == '62')
        {
            $numb = '0'.substr($numb, 2);
        }
        return $numb;
    }
}

if (!function_exists('penyebut'))
{
    function penyebut($nilai)
    {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}
		return $temp;
	}
}

if (!function_exists('terbilang'))
{
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}
		return $hasil;
	}
}
