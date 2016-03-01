###如果要使用swoole http server .
#####1.请把Group框架的composer.json文件里的require  "group/group-framework": "~1.1.6" 版本改为 "group/group-framework": "dev-swoole-http-server"
#####2.nginx配置文件示例在下方
#####3.php index.php &
#####4.访问http://127.0.0.1:9776
#####常规	
	Server Software:        openresty/1.9.7.1
	Server Hostname:        127.0.0.1
	Server Port:            82

	Document Path:          /
	Document Length:        1660 bytes

	Concurrency Level:      100
	Time taken for tests:   2.016 seconds
	Complete requests:      2000
	Failed requests:        0
	Keep-Alive requests:    2000
	Total transferred:      3980000 bytes
	HTML transferred:       3320000 bytes
	Requests per second:    992.15 [#/sec] (mean)
	Time per request:       100.791 [ms] (mean)
	Time per request:       1.008 [ms] (mean, across all concurrent requests)
	Transfer rate:          1928.10 [Kbytes/sec] received

	Connection Times (ms)
	              min  mean[+/-sd] median   max
	Connect:        0    0   0.5      0       3
	Processing:    11   98  35.6     90     355
	Waiting:       11   98  35.6     90     355
	Total:         14   99  35.5     90     355


	Server Software:        nginx/1.8.0
	Server Hostname:        127.0.0.1
	Server Port:            82

	Document Path:          /
	Document Length:        1660 bytes

	Concurrency Level:      100
	Time taken for tests:   2.087 seconds
	Complete requests:      2000
	Failed requests:        0
	Keep-Alive requests:    2000
	Total transferred:      3968000 bytes
	HTML transferred:       3320000 bytes
	Requests per second:    958.32 [#/sec] (mean)
	Time per request:       104.350 [ms] (mean)
	Time per request:       1.043 [ms] (mean, across all concurrent requests)
	Transfer rate:          1856.74 [Kbytes/sec] received

	Connection Times (ms)
	              min  mean[+/-sd] median   max
	Connect:        0    0   0.5      0       3
	Processing:    10  102  30.8    101     350
	Waiting:       10  102  30.8    101     350
	Total:         13  102  30.6    101     350



##### 使用swoole http server后，性能提升近一倍多

	Server Software:        nginx/1.8.0
	Server Hostname:        127.0.0.1
	Server Port:            9776

	Document Path:          /
	Document Length:        1660 bytes

	Concurrency Level:      100
	Time taken for tests:   1.022 seconds
	Complete requests:      2000
	Failed requests:        0
	Keep-Alive requests:    2000
	Total transferred:      3616000 bytes
	HTML transferred:       3320000 bytes
	Requests per second:    1956.02 [#/sec] (mean)
	Time per request:       51.124 [ms] (mean)
	Time per request:       0.511 [ms] (mean, across all concurrent requests)
	Transfer rate:          3453.61 [Kbytes/sec] received

	Connection Times (ms)
	              min  mean[+/-sd] median   max
	Connect:        0    0   0.4      0       3
	Processing:     9   50  12.6     49      91
	Waiting:        9   50  12.6     49      91
	Total:         12   50  12.5     49      91

附nginx 配置：

	server {

	    listen 9776;
	    root /var/www/Group;
	    server_name local.swoole.com;


	    location / {
	        try_files $uri @rewriteapp;
	    }
	    location @rewriteapp {
	        rewrite ^(.*)$ /index.php$1 last;
	    }
	    location ~ ^/(index)\.php(/|$) {
	        proxy_set_header X-Real-IP  $remote_addr;
	        proxy_set_header X-Forwarded-For $remote_addr;
	        proxy_set_header Host $host;
	        proxy_pass http://127.0.0.1:9777;
	    }

	    location ~* \.(jpg|jpeg|gif|png|ico|swf)$ {
	        # 过期时间为3年
	        expires 3y;

	        # 关闭日志记录
	        access_log off;

	        # 关闭gzip压缩，减少CPU消耗，因为图片的压缩率不高。
	        gzip off;
	    }

	    # 配置css/js文件
	    location ~* \.(css|js)$ {
	        access_log off;
	        expires 3y;
	    }
	}
