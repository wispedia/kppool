<?php

namespace Kppool\Forker;


interface ProcessPoolInterface {


	/**
	 * @desc 主函数
	 * @return mixed
	 */
	public function run();

	/**
	 * @desc 注册回调函数至任务池
	 * @param callable $func
	 * @param array    $args
	 *
	 * @return bool
	 */
	public function register(callable $func, ...$args): bool;

	/**
	 * @desc 新建一个进程到进程池
	 *
	 * @return mixed
	 */
	public function create();

	/**
	 * @desc 判断进程是否运行
	 *
	 * @param int $pid
	 *
	 * @return bool
	 */
	public function isRunning(int $pid): bool;

	/**
	 * @desc 判断进程是否空闲
	 *
	 * @param int $pid
	 *
	 * @return bool
	 */
	public function isIdle(int $pid): bool;

	/**
	 * @desc  向子进程中投递消息
	 *
	 *
	 * @param int $pid
	 * @param     $value
	 *
	 * @return bool
	 */
	public function pub(int $pid, $value): bool;

	/**
	 * @desc 初始化进程池，为每个进程分配channel
	 *
	 * @return mixed
	 */
	public function init();

}