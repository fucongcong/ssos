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

