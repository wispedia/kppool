<?php

namespace Kppool\Forker;


class PcntlProcessPool implements ProcessPoolInterface {

	//	进程表
	private $process_table;
	//	最大进程数量
	private $max_process_num;
	//	任务表
	private $task_table;
	//	channel的class名
	private $channel_class;

	public function __construct(string $channel, $max_process_num) {
		$this->max_process_num = $max_process_num;
		$this->task_table      = [];
		$this->process_table   = [];
		$this->channel_class   = $channel;
		$this->init();
	}


	public function run() {

	}


	public function init() {
		$i = 0;
		while ($i < $this->max_p_num) {
			if ($this->create()) {
				$i++;
			}
		}
	}

	public function register(callable $func, ...$args): bool {
		$item = [
			"func" => $func,
			"args" => $args,
		];
		array_unshift($this->task_table, $item);

		return true;
	}

	public function isRunning(int $pid): bool {
		$result = pcntl_waitpid($pid, $status, WNOHANG);

		return ($result === 0);
	}

	public function create() {
		$channel_name = uniqid("channel_", true);
		$pid          = pcntl_fork();
		if ($pid == 0) {
			//				子进程处理
			$channel       = new $this->channel_class($channel_name);
			$child_process = new Process();
			//				子进程会阻塞在这里，永远不退出
			$child_process->subscribe($channel);
		}

		if ($pid > 0) {
			$this->process_table[ $pid ] = new $this->channel_class($channel_name);

			return true;
		}

		return false;
	}

	public function isIdle(int $pid): bool {
		$channel = $this->process_table[ $pid ];

		return $channel->isEmpty();
	}

	public function pub(int $pid, $value): bool {
		$channel = $this->process_table[ $pid ];

		return $channel->write($value);
	}

}