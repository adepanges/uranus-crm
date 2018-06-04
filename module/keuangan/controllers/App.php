<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Keuangan_Controller {

	public function index()
	{
        if(!$this->_is_sso_signed())
        {
            redirect($this->config->item('sso_link').'/auth/log/out');
        }
        $menu = $this->session->userdata('menu');

        if(isset($menu['finance.php']) && !empty($menu['finance.php']))
        {
            redirect($menu['finance.php'][0]['menu_link']);
        }
	}
}
