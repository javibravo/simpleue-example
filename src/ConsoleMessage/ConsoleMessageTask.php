<?php
/**
 * Created by PhpStorm.
 * User: jbravo
 * Date: 25/05/15
 * Time: 11:39
 */

namespace ConsoleMessage;

use SimplePhpQueue\Task\Task;

class ConsoleMessageTask implements  Task {

    private $decodedMessage;
    /**
     * @param $task : JSON
     *  {
     *      "text" : "message text",
     *      "color" : ["green", "red", "blue"]
     *  }
     */
    public function manage($message) {
        if (!$this->validateMessage($message))
            return FALSE;
        $this->printMessage();
        return TRUE;
    }

    private function validateMessage($message) {
        $this->decodedMessage = json_decode($message, true);
        if (!array_key_exists('text', $this->decodedMessage))
            return FALSE;
        return TRUE;
    }

    private function printMessage() {
        echo $this->decodedMessage['text']."\n";
    }
}