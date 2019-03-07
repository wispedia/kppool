# kppool
PHP进程池

## 快速开始

### 依赖

- php>=7.0.0
- ext-apcu>=^5.1
- ext-pcntl>=^7.2

### 安装依赖
```bash
    composer require wispedia/kppool
```
### 示例
```php
    use Kppool\Forker\PcntlProcessPool;
    use Kppool\Channel\ApcuChannel;
    
    
    function echo_hello($a) {
    	var_dump($a);
    }
    
    // 第二个参数为初始化进程的数量
    $process_pool = new PcntlProcessPool(ApcuChannel::class, 4);
    
    for ($i = 1; $i <= 1000; $i++) {
    	$process_pool->register("echo_hello", "hello");
    }
    // run方法会等待所有register的任务完成之后才返回
    $process_pool->run();
```