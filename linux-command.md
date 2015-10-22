
    top 查看cpu
    sar　cpu历史
    free -m 查看使用率
    ps -ef 查看进程
    df -h 硬盘空间
    netstat -tun tcp 连接数

    netstat -n | awk '/^tcp/ {++state[$NF]} END {for(key in state) print key,"t",state[key]}'

    sudo apt-get purge php5-fpm　卸载fpm
    cd ~/.ssh 进入ssh目录
    pstree | grep snmp　查看相关进程是否启动
    iptables -I INPUT -s 222.186.129.5 -j DROP　封单个ip
    iptables -D INPUT -s 58.100.217.33 -j DROP 解封
    iptables -F 全清掉了
    netstat -nat|grep -i "80"|wc -l　查看端口80的tcp连接数
    ps -ef | grep php5-fpm　查看摸个进程
    kill -9 进程ID 杀进程

    lsof -i: 9501 查看端口下得进程

    //tcp 抓包命令

    sudo tcpdump -i any tcp port 9396

