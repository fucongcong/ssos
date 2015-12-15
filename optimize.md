php-fpm 优化（进程数等）
ngnix优化

mysql优化（连接数上限）
innodb_buffer_pool_size = 1G
key_buffer_size　一般可以取数据库可用内存的２５％
mysqld --verbose --help 查看mysql系统变量

linux内核优化
php.ini 开启opcache扩展，加速php
具体根据服务器配置优化

增大ulimit


网站性能监控
http://tpm.oneapm.com/

代码优化
xhprof

