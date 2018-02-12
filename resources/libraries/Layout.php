<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout {

    function __construct ()
    {
        $this->_ci = &get_instance();
    }

    function view ($view, $data = NULL, $template = 'default', $returned = FALSE)
    {
        if(isset($data['title']) && !empty($data['title'])) $data['title'] = $data['title'] .' - '. $this->_ci->config->item('app_name');
        else $data['title'] = $this->_ci->config->item('app_name');

        $data['_header'] = $this->_ci->load->view('main-inc/'.$template.'/header', $data, TRUE);

        if(is_array($view))
        {
            $data['_content'] = array();
            foreach ($view as $t)
            {
                array_push($data['_content'], $this->_ci->load->view('inc/'.$t, $data, TRUE));
            }
        }
        else
        {
            if($view !== '')
                $data['_content'] = $this->_ci->load->view('inc/'.$view, $data, TRUE);
            else
                $data['_content'] = "";
        }
        $data['_footer'] = $this->_ci->load->view('main-inc/'.$template.'/footer', $data, TRUE);
        $this->_ci->load->view('layout/default', $data, $returned);
    }
}
