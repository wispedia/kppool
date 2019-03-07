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
		while (true) {
			if (empty($this->task_table)) {
				goto WAIT;
			}
			foreach ($this->process_table as $pid => $channel) {
				if ($this->isRunning($pid)) {
					if ($this->isIdle($pid)) {
						$task = array_shift($this->task_table);
						if (empty($task) || empty($this->task_table)) {
							goto WAIT;
						}
						$this->pub($pid, $task);
					}
				} else {
					$this->create();
				}
			}
			continue;

			WAIT:
			$this->wait();
			break;
		}
	}

	public function wait() {
		while (true) {
			$all_finish = false;
			foreach ($this->process_table as $pid => $channel) {
				if (!$this->isIdle($pid)) {
					$all_finish = true;
				}
			}
			if (!$all_finish) {
				break;
			}
		}
	}

	public function init() {
		$i = 0;
		while ($i < $this->max_process_num) {
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

		if ($result !== 0) {
			unset($this->process_table[ $pid ]);
		}

		return ($result === 0);
	}

	public function create() {
		$channel_name = uniqid("channel_", true);
		$channel_name = md5($channel_name);
		$ppid         = posix_getpid();
		$pid          = pcntl_fork();
		if ($pid == 0) {
			//				子进程处理
			$channel       = new $this->channel_class($channel_name);
			$child_process = new Process($ppid);
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
		$res     = $channel->write($value);

		return $res;
	}

	public function __destruct() {
		foreach ($this->process_table as $pid => $channel) {
			posix_kill($pid, SIGKILL);
		}
	}

}