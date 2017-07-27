 -c 200 指定并发数200
-r 5 指定测试的次数5
-f urls.txt 制定url的文件
-i internet系统，随机发送url
-b 请求无需等待 delay=0
-t 5 持续测试5分钟
# -r和-t一般不同时使用


//随机选取urls.txt中列出所有的网址
 siege -c 10 -r 1 -f urls.txt -i -b