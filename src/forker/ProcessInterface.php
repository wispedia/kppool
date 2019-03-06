<?php

namespace App\Scheduler\Libs\Forker;

use App\Scheduler\Libs\Channel\ChannelInterface;

interface ProcessInterface {

	public function subscribe(ChannelInterface $channel);

	public function call(callable $func, ...$args);
}