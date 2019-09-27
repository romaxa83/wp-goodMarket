<?php

namespace backend\modules\blog\type;

class MessageType
{
    private $type;
    private $message;

    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
}
