<?php

namespace App\Scheduler\Libs\Channel;

class ApcuChannel extends CommonChannel implements ChannelInterface {

	public function read() {
		do {
			$value = apcu_fetch($this->project);
			usleep(1);
		} while ($value === false);

		return $value;
	}

	public function write($value): bool {
		return apcu_add($this->project, $value);
	}

	public function feedBack() {
		apcu_delete($this->project);
	}

	public function isEmpty(): bool {
		return apcu_exists($this->project);
	}
}