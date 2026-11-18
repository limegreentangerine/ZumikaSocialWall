<?php

namespace ZumikaSocialWall\Log;

use ClassKit\Log\Logger;

class ZumikaLogger extends Logger
{
    public function __construct()
    {
        parent::__construct('zumika');
    }
}
