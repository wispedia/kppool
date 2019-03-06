<?php

namespace Kppool\Forker;

use Kppool\Channel\ChannelInterface;

interface ProcessInterface {

	public function subscribe(ChannelInterface $channel);

	public function call(callable $func, ...$args);
}