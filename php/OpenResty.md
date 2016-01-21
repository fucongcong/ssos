#####昨天看到OpenResty这个名词，然后简单了解了一下发现目前许多大公司，都在使用这一个基于nginx和lua的web server高性能解决方案。

#####所以就去下了一个玩了一下。

#####安装
[openresty](http://openresty.org/)

#####它基于nginx，所以之前跑在nginx的web应用，配置可以全部迁移过来
#####那么它的优势呢，我简单的看了下，也就是可以基于lua，扩展一套非阻塞的web框架，只能性能方面 我简单做了一个测试

	2.8GHz 双核 Intel Core i5 处理器  8G内存

	Server Software:        openresty/1.9.7.1
	Server Hostname:        127.0.0.1
	Server Port:            82

	Document Path:          /
	Document Length:        21 bytes

	Concurrency Level:      100
	Time taken for tests:   5.625 seconds
	Complete requests:      200000
	Failed requests:        0
	Keep-Alive requests:    198025
	Total transferred:      34590125 bytes
	HTML transferred:       4200000 bytes
	Requests per second:    35555.09 [#/sec] (mean)
	Time per request:       2.813 [ms] (mean)
	Time per request:       0.028 [ms] (mean, across all concurrent requests)
	Transfer rate:          6005.15 [Kbytes/sec] received

	Connection Times (ms)
	              min  mean[+/-sd] median   max
	Connect:        0    0   0.1      0       3
	Processing:     1    3   2.4      3     109
	Waiting:        1    3   2.4      3     109


#####swoole的http server对比了一下

	Server Software:        swoole-http-server
	Server Hostname:        127.0.0.1
	Server Port:            9777

	Document Path:          /
	Document Length:        22 bytes

	Concurrency Level:      100
	Time taken for tests:   3.035 seconds
	Complete requests:      200000
	Failed requests:        0
	Keep-Alive requests:    200000
	Total transferred:      35000000 bytes
	HTML transferred:       4400000 bytes
	Requests per second:    65904.48 [#/sec] (mean)
	Time per request:       1.517 [ms] (mean)
	Time per request:       0.015 [ms] (mean, across all concurrent requests)
	Transfer rate:          11262.97 [Kbytes/sec] received

	Connection Times (ms)
	              min  mean[+/-sd] median   max
	Connect:        0    0   0.0      0       3
	Processing:     0    2   2.1      1      67
	Waiting:        0    2   2.1      1      67
	Total:          0    2   2.1      1      67

#####总的来说，如果做移动api，swoole的性能更强劲，而且学习成本相对于lua更低。

