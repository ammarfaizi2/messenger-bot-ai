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
    public function __construct($config)
    {
        Messenger::setupWebhook(VALIDATION_TOKEN);
        defined('data') or define('data', __DIR__ . '/data');
        is_dir(data) or mkdir(data);
        $this->config        = $config;
        $this->ai            = new AI();
        $this->messenger    = new Messenger();
        $this->input        = json_decode(Messenger::get_input(), 1);
    }

    public function run()
    {
        $this->action();
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
                        $st        = $this->ai->prepare($message, $this->messenger->get_sender_name($to));
                        if ($st->execute()) {
                            $reply_msg  = $st->fetch_reply();
                            if (is_array($reply_msg)) {
                                print $this->messenger->send_message($to, $reply_msg[1]);
                                print $this->messenger->send_image($to, $reply_msg[0]);
                            } elseif (empty($reply_msg)) {
                                print $this->messenger->send_message($to, "Mohon maaf saya belum paham \"{$message}\".");
                            } else {
                                print $this->messenger->send_message($to, $reply_msg);
                            }
                        } else {
                            print $this->messenger->send_message($to, "Mohon maaf saya belum paham \"{$message}\".");
                        }
                    }
                }
            }
        }
    }




    public function __debugInfo()
    {
    }
}
