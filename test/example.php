<?php

require_once "../vendor/autoload.php";

use Kppool\Forker\PcntlProcessPool;
use Kppool\Channel\ApcuChannel;


$process_pool = new PcntlProcessPool(ApcuChannel::class, 4);

for ($i = 1; $i <= 1000; $i++) {
	$process_pool->register("echo_hello", "hello");
}

// 执行任务
$process_pool->run();


for ($i = 1; $i <= 1000; $i++) {
	$process_pool->register("echo_hello", "hello2");
}
$process_pool->run();

function echo_hello($a) {
	var_dump($a);
}