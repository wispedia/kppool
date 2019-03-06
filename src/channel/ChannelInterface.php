<?php

namespace App\Scheduler\Libs\Channel;

interface ChannelInterface {


	/**
	 * @desc 向channel中写入消息，channel中只允许有一条消息
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public function write($value): bool;

	/**
	 * @desc 从channel中读取消息，读取之后需要删除channel中的消息,如果channel中没有消息，会阻塞在这里
	 * @return mixed
	 *
	 */
	public function read();


	/**
	 * @desc 从channel中消费，并且处理完成之后会feedback
	 * @return mixed
	 */
	public function feedBack();


	/**
	 * @desc 判断这个channel中的消息是否为空
	 * @return mixed
	 */
	public function isEmpty(): bool;
}