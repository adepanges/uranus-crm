<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Eins_log {
    protected
        $conf = array(),
        $log_folder = 'logs/',
        $log_file = '',
        $ci,
        $thread = 0;

    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->helper('url');
    }

    function init($conf=array())
    {
        $this->conf = array(
            'log_name' => (isset($conf['log_name']) && !empty($conf['log_name']))?$conf['log_name']:'',
            // 'method_name' => (isset($conf['method_name']) && !empty($conf['method_name']))?$conf['method_name']:'',
            // 'message' => (isset($conf['message']) && !empty($conf['message']))?$conf['message']:''
        );
        $this->ci->load->helper('file');
        $this->log_folder = APPPATH.$this->log_folder;
        $this->_init_folder();
        $this->_init_log();
    }

    protected function _init_folder()
    {
        $this->log_folder = $this->log_folder.$this->conf['log_name'];
        if(!empty($this->conf['log_name']))
        {
            if(!file_exists($this->log_folder))
            {
                mkdir($this->log_folder);
            }
            $this->log_folder = $this->log_folder.'/';
        }
    }

    protected function _init_log()
    {
        $this->log_file = $this->log_folder.'LOG_'.date('Ymd');
        $this->thread = str_replace('.','', microtime(TRUE)).rand(1000, 9999);
        // $this->write(__METHOD__, 'INIT LOG CONTENT MESSAGE' , array(
        //     'success' => 1,
        //     'msg' => 'TEST LOG ON INIT'
        // ));
    }

    public function write($class_method = '', $msg, $data = array())
    {
        $message = "## [".date('H:i:s')."][{$this->thread}]";
        if(!empty($class_method))
        {
            $message .= "[$class_method]";
        }

        if(!empty($msg))
        {
            $message .= "[$msg]";
        }

        if(!empty($data))
        {
            if(is_array($data) || is_object($data))
            {
                $message .= "\n\t[".json_encode($data)."]";
            }
            else
            {
                $message .= "\n\t[".$data."]";
            }
        }

        $message .= "\n";
        return write_file($this->log_file, $message, 'a');
    }

    public function http_response($class_method, $response = array()){
        $this->write($class_method, "HTTP Response: ", $response);
    }

    public function http($class_method, $http_method, $endpoint, $header = array(), $params = array()){
        $this->write($class_method, "HTTP REQUEST START");
        $this->write($class_method, "HTTP: $http_method");
        $this->write($class_method, "HTTP Endpoint: $endpoint");
        if(!empty($header)){
            $this->write($class_method, "HTTP Header: ", $header);
        }
        $this->write($class_method, "HTTP Params: ", $params);
        $this->write($class_method, "HTTP REQUEST END");
    }
}
