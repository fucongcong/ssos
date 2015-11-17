####创建数据库：

    CREATE DATABASE `{DATABASE_NAME}` DEFAULT CHARACTER SET utf8 ;


####创建数据库帐号，并授权访问某数据库：

    GRANT ALL PRIVILEGES ON `{DATABASE_NAME}`.* TO '{USER}'@'{HOST}' IDENTIFIED BY '{PASSWORD}';

    注意：my.cnf中的

    bind-address = 127.0.0.1　必须注释，才可以授权其他ip登陆数据库

####授权帐号访问某数据库：

    GRANT ALL PRIVILEGES ON `{DATABASE_NAME}`.* TO '{USER}'@'{HOST}';



####修改帐号密码：

    USE mysql;
    UPDATE `user` SET password=PASSWORD("{NEW_PASSWORD}") WHERE user = '{USER}';
    FLUSH PRIVILEGES;

####查看用户授权表

    select user,host,password from mysql.user;

####bin-log日志操作

    flush logs;产生一个此时间点的新的bin-log日志
    show master status; 查看最后一个bin-log日志
    reset master ;　清空所有bin-log日志
    mysqlbinlog --no-defaults mysql-bin.000001 ;查看bin-log
    备份数据
    mysqldump -uroot -p123  DATABASE_NAME -l -F '/tmp/text.sql'
    导入数据
    mysql -uroot -p123 DATABASE_NAME -v -f < /tmp/text.sql
    恢复bin-log数据
    mysqlbinlog --no-defaults mysql-bin.000001 ｜mysql -u root -p 123 DATABASE_NAME
    mysqlbinlog --no-defaults mysql-bin.000001  --start-position = "100" --stop-position = "200" ｜mysql -u root -p 123 DATABASE_NAME
    mysqlbinlog --no-defaults mysql-bin.000001  --start-date = "2015-05-05 12:12:12" --stop-date = "2015-05-05 13:13:!3" ｜mysql -u root -p 123 DATABASE_NAME


####配置slave,一下信息为主服务器ip,以及授权的账号．

    CHANGE MASTER TO
    MASTER_HOST = '192.168.1.214',MASTER_PORT = 3306,MASTER_USER = 'repl_user',MASTER_PASSWORD = 'YOURPASSWORD';

    START SLAVE;

####查看slave状态

    show slave status\G;



###mysql主从服务器在线上运行出现的问题

#####slave同步master时，网络io瓶颈或磁盘io瓶颈导致数据同步出现延迟时，部分业务逻辑会因为这个原因而导致数据重复插入。

    那么如何解决此类问题呢，因为数据量到一定程度时，或者说用户量到达一定程度时，必然会出现数据同步有延迟的问题！
    现在能想到的办法：
    1.使用缓存将数据存储，那么在请求读取数据的时候，读服务器数据还没同步过来时，会从缓存返回数据，这样可以缓冲同步时间。
    缺陷：1.如果所有数据都存到缓存，缓存占用的内存会过大，这样成本会不会太高？2.如果使用了缓存的主从，会不会同样出现刚才的问题，那又应该如何取解决？
    2.使用负载把实时性比较强的数据的读写丢到master，而相对及时性要求并不大的数据读取就丢给slave，这样是不会出现上面的问题。
    缺陷：这样势必master的压力会相对比主从的读写会更大一些，性能会相对差一些，但是可用性大大提升。

#####master宕机怎么办？目前还没有遇到

    宕机是肯定会出现的，那么如何更好的降低宕机时产生的影响，以及保证网站正常工作呢？
    服务器集群是肯定需要的，这里就会出现双主和数据库分区概念。
    1.双主服务器，那么一旦一台master挂了，还有另一台可以正常运行。将风险降低
    2.数据库分库分区，如果我把每张表都拆出来，分别放到每台服务器去跑，这样一旦一台挂了，受到影响的仅仅只会是和它有关的业务，其他业务可以正常使用。而如果不拆分，那么一旦服务器挂了，整个网站就瘫痪了。
    那么接下来要想想这两种解决方法的缺陷了。
    1.双主服务器如何做数据同步？
    2.数据库分库分区如何做到数据层很好的支持，当某个业务量增大时，还是会出现上面的问题。成本很高


####Linux系统中的数据库迁移

    1.导出整个数据库
    mysqldump -u 用户名 -p 数据库名 > 导出的文件名
    mysqldump -u root  -p  databaseName > fileName.sql

    2.导入数据库
    常用source 命令
    进入mysql数据库控制台，
    如mysql -u root -p

    mysql>use 数据库

    然后使用source命令，后面参数为脚本文件（如这里用到的.sql）
    mysql>source /var/www/fileName.sql



