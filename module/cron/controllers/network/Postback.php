<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postback extends Cron_Controller {

    function __construct()
    {
        parent::__construct();
        $this->eins_log->init([
            'log_name' => 'network_postback'
        ]);
    }

	public function send()
	{
        $this->load->model('network_model');
        $queue = $this->network_model->get_queue();
        $this->eins_log->write('info', 'INIT NETWORK POSBACK');

        if($queue->num_rows())
        {
            $queue_data = $queue->result();
            $process_id = [];
            foreach ($queue_data as $key => $value) {
                $process_id[] = $value->process_id;
            }
            $this->network_model->upd_process_postback($process_id);
            $this->eins_log->write('info', 'NETWORK POSBACK WILL BE PROCESS', $process_id);

            foreach ($queue_data as $key => $value) {
                $params = [
                    'order_id' => $value->order_id,
                    'network_id' => $value->network_id,
                    'network_postback_id' => $value->network_postback_id,
                    'order_network_id' => $value->order_network_id,
                    'process_id' => $value->process_id,
                    'event_id' => $value->event_id,
                    'orders_trigger' => $value->orders_trigger,
                    'event_name' => $value->event_name,
                    'catch_data' => $value->catch,
                    'url' => bind_string($value->link, (array) json_decode($value->catch)),
                    'network_name' => $value->name,
                    'status' => 1
                ];
                $this->eins_log->write('info', 'NETWORK '.$value->name.' POSTBACK REQ :', $params['url']);
                $resp = Unirest\Request::get($params['url']);
                if($resp) $params['postback_response'] = $resp->raw_body;
                $this->eins_log->write('info', 'NETWORK '.$value->name.' POSTBACK RES :', $params['postback_response']);

                $this->eins_log->write('info', 'ADD LOG POSTBACK', $params);
                $this->network_model->add_network_postback($params);
            }
        }
        else
        {
            $this->eins_log->write('info', 'QUEUE POSTBACK NOT AVAILABLE');
        }
	}
}
