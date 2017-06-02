<?php

namespace System;

defined("VALIDATION_TOKEN") or die("VALIDATION_TOKEN is not defined !");

use AI\AI;
use Messenger\Messenger;

/**
 *
 * @author	Ammar Faizi	<ammarfaizi2@gmail.com>
 *
 */

class ActionHandler
{

	/**
	 *	
	 * @var	array
	 */
	private $config;

	/**
	 *
	 * @var	AI\AI
	 */
	private $ai;

	/**
	 *
	 * @var Messenger\Messenger
	 */
	private $messenger;

	/**
	 *
	 * @var	array
	 */
	private $input;


	/**
	 *
	 * @param	array	$this->config
	 */
	public function __construct($this->config)
	{
		Messenger::setupWebhook(VALIDATION_TOKEN);
		$this->config		= $this->config;
		$this->ai			= new AI();
		$this->messenger	= new Messenger();
		$this->input		= json_decode(Messenger::get_input(), 1);
	}

	public function run()
	{

	}

	private function action()
	{
		if (isset($this->input['entry'])) {
		    foreach ($this->input['entry'] as $key => $value) {
		        if (isset($this->config[$value['id']]) and isset($value['messaging'])) {
		            $this->messenger->set_token($this->config[$value['id']]['token']);
		            print $this->messenger->set_welcome_msg($key, $this->config[$value['id']]['welcome_msg']);
		            foreach ($value['messaging'] as $value2) {
		                $to         = $value2['sender']['id'];
		                $message    = $value2['message']['text'];
		                $st 		= $this->ai->prepare();
		                $reply_msg  = $message;
						print $this->messenger->send_message($to, $reply_msg);
		            }
		        }
		    }
		}
	}




	public function __debugInfo()
	{
	}
}