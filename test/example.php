<?php

require_once "../vendor/autoload.php";

use Kppool\Forker\PcntlProcessPool;
use Kppool\Channel\ApcuChannel;


$process_pool = new PcntlProcessPool(ApcuChannel::class, 10);