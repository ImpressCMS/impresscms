<?php

/**
* Creates response of server sent events type
 *
 * @author      Raimondas Rimkevičius <mekdrop@impresscms.org>
 * @package	ICMS\Response
 */
class icms_response_Events
    extends icms_response_Text {

    /**
    * Mimetype for this response
    */
    const CONTENT_TYPE = 'text/event-stream';

    const MSG_TYPE_NORMAL = 0;
    const MSG_TYPE_ERROR = 1;
    const MSG_TYPE_INFO = 2;
    const MSG_TYPE_WARNING = 3;

    /**
    * Constructor
    */
    public function __construct() {
        parent::__construct(null, null, [
            'Cache-Control: no-cache'
        ]);
    }

    /**
    * Send data
     *
    * @param mixed             $data
    * @param int|string|null   $id
    */
    public function sendData($data, $id = null) {
        if ($id !== null) {
            echo 'id: ' . $id . "\n";
        }
        echo 'data: ' . implode("\ndata: " . explode("\n", json_encode($data))) . "\n\n";
        ob_flush();
        flush();
    }

    /**
    * Send custom event
     *
    * @param string            $event
    * @param mixed             $data
    * @param int|string|null   $id
    */
    public function sendCustomEvent($event, $data, $id = null) {
        echo 'event: ' . $event . "\n";
        $this->sendData($data, $id);
    }

    /**
    * Send message type event
     *
    * @param string            $message
    * @param int               $type
    * @param int|string|null   $id
    */
    public function sendMessage($message, $type = icms_response_Events::MSG_TYPE_NORMAL, $id = null) {
        $this->sendCustomEvent('message', compact('message', 'type'), $id);
    }

}