<?php

namespace Kppool\Forker;


use Kppool\Channel\ChannelInterface;

class Process implements ProcessInterface {

	public function subscribe(ChannelInterface $channel) {
		while (true) {
			try {
				$msg = $channel->read();
				if (isset($msg["func"]) && !is_callable($msg["func"])) {
					$args = isset($msg["args"]) ? $msg["args"] : [];
					$this->call($msg["func"], $args);
				}
				$channel->feedBack();
			} catch (\Exception $e) {

			}
		}
	}

	public function call(callable $func, ...$args) {
		$func(...$args);
	}
}