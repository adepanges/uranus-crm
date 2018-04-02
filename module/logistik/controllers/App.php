<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Logistik_Controller {

	public function index()
	{
        if(!$this->_is_sso_signed())
        {
            redirect($this->config->item('sso_link').'/auth/log/out');
        }
        $menu = $this->session->userdata('menu');

        if(isset($menu['logistik.php']) && !empty($menu['logistik.php']))
        {
            redirect($menu['logistik.php'][0]['menu_link']);
        }
        else
        {
            redirect('packing_v1');
        }
	}
}
