//切换显示
.mode line

//本机配的hosts
select * from etc_hosts;

//本机信息
select * from system_info;

//本机监听的端口
select * from listening_ports;

//所有用户
SELECT * FROM users;

//本地服务
SELECT DISTINCT processes.name, listening_ports.port, processes.pid
  FROM listening_ports JOIN processes USING (pid)
  WHERE listening_ports.address = '0.0.0.0';

//mac地址异常
SELECT address, mac, COUNT(mac) AS mac_count
  FROM arp_cache GROUP BY mac
  HAVING count(mac) > 1;




arp_cache
地址解析缓存，包括静态和动态（来自ARP，NDP）。

柱   类型  描述
address TEXT    IPv4地址目标
mac TEXT    广播地址的MAC地址
interface   TEXT    MAC的网络接口
permanent   TEXT    1为真，0为假



chrome_extensions
Chrome浏览器扩展程序

柱   类型  描述
uid BIGINT  拥有扩展名的本地用户
name    TEXT    分机显示名称
identifier  TEXT    分机标识符
version TEXT    扩展提供的版本
description TEXT    扩展选项说明
locale  TEXT    扩展名支持的默认语言环境
update_url  TEXT    扩展提供的更新URI
author  TEXT    可选的扩展作者
persistent  INTEGER 1如果扩展名在所有其他选项卡上保持不变0
path    TEXT    扩展文件夹的路径

select * from users join chrome_extensions using (uid)


curl
执行http请求并返回关于它的统计信息。

柱   类型  描述
url TEXT    请求的网址
method  TEXT    请求的HTTP方法
user_agent  TEXT    用于请求的用户代理字符串
response_code   INTEGER 响应的HTTP状态码
round_trip_time BIGINT  完成请求所花费的时间
bytes   BIGINT  响应中的字节数
result  TEXT    HTTP响应正文

select url, round_trip_time, response_code from curl where url = 'https://github.com/facebook/osquery'


etc_hosts
行解析/ etc / hosts。

柱   类型  描述
address TEXT    IP地址映射
hostnames   TEXT    原始主机映射

hash
文件系统哈希数据。

柱   类型  描述
path    TEXT    必须提供一个路径或目录
directory   TEXT    必须提供一个路径或目录
md5 TEXT    提供的文件系统数据的MD5哈希
sha1    TEXT    提供的文件系统数据的SHA1散列
sha256  TEXT    提供的文件系统数据的SHA256散列

select * from hash where path = '/etc/passwd'
select * from hash where directory = '/etc/'

listening_ports
监听（绑定）网络套接字/端口的进程。

柱   类型  描述
pid INTEGER 进程（或线程）ID
port    INTEGER 传输层端口
protocol    INTEGER 传输协议（TCP / UDP）
family  INTEGER 网络协议（IPv4，IPv6）
address TEXT    绑定的具体地址


logged_in_users
系统上具有活动shell的用户。

柱   类型  描述
type    TEXT    登录类型
user    TEXT    用户登录名
tty TEXT    设备名称
host    TEXT    远程主机名
time    INTEGER 输入时间
pid INTEGER 进程（或线程）ID



process_open_sockets
在系统上有开放网络套接字的进程。

柱   类型  描述
pid INTEGER 进程（或线程）ID
fd  BIGINT  套接字文件描述符号码
socket  BIGINT  套接字句柄或inode号码
family  INTEGER 网络协议（IPv4，IPv6）
protocol    INTEGER 传输协议（TCP / UDP）
local_address   TEXT    套接字本地地址
remote_address  TEXT    套接字远程地址
local_port  INTEGER 套接字本地端口
remote_port INTEGER 套接字远程端口
path    TEXT    对于UNIX套接字（family = AF_UNIX），域路径
select * from process_open_sockets where pid = 1

processes
主机系统上的所有正在运行的进程。

柱   类型  描述
pid BIGINT  进程（或线程）ID
name    TEXT    进程路径或速记argv [0]
path    TEXT    执行二进制文件的路径
cmdline TEXT    完成argv
state   TEXT    进程状态
cwd TEXT    处理当前工作目录
root    TEXT    处理虚拟根目录
uid BIGINT  未签名的用户ID
gid BIGINT  未签名的组ID
euid    BIGINT  未签名的有效用户ID
egid    BIGINT  未签名的有效组ID
suid    BIGINT  未签名保存的用户ID
sgid    BIGINT  未签名保存的组ID
on_disk INTEGER 进程路径存在yes = 1，no = 0，unknown = -1
wired_size  BIGINT  进程使用的不可分配内存字节
resident_size   BIGINT  进程使用的私有内存字节
total_size  BIGINT  总的虚拟内存大小
user_time   BIGINT  在用户空间中花费CPU时间
system_time BIGINT  在内核空间中花费的CPU时间
start_time  BIGINT  从启动（非休眠​​）开始，以秒为单位启动
parent  BIGINT  处理父级的PID
pgroup  BIGINT  进程组
threads INTEGER 进程使用的线程数
nice    INTEGER 处理好级别（-20至20，默认为0）
select * from processes where pid = 1


startup_items
应用程序和二进制文件设置为用户/登录启动项目。

柱   类型  描述
name    TEXT    启动项目的名称
path    TEXT    启动项目的路径
args    TEXT    提供给启动可执行文件的参数
type    TEXT    启动项目或登录项目
source  TEXT    包含启动项目的目录或plist
status  TEXT    启动状态; 启用或禁用
username    TEXT    与启动项目关联的用户

system_info
系统信息进行识别。

柱   类型  描述
hostname    TEXT    网络主机名包括域
uuid    TEXT    系统提供的唯一ID
cpu_type    TEXT    CPU类型
cpu_subtype TEXT    CPU子类型
cpu_brand   TEXT    CPU品牌字符串，包含供应商和型号
cpu_physical_cores  INTEGER CPU物理内核的最大数量
cpu_logical_cores   INTEGER 最大数量的CPU逻辑核心
physical_memory BIGINT  总物理内存以字节为单位
hardware_vendor TEXT    硬件或主板供应商
hardware_model  TEXT    硬件或主板型号
hardware_version    TEXT    硬件或电路板版本
hardware_serial TEXT    设备或主板序列号
computer_name   TEXT    友好的电脑名称（可选）
local_hostname  TEXT    本地主机名（可选）

uptime
跟踪上次启动以来的时间。

柱   类型  描述
days    INTEGER 正常运行时间
hours   INTEGER 正常运行时间
minutes INTEGER 几分钟的正常运行时间
seconds INTEGER 秒的正常运行时间

users
本地系统用户。

柱   类型  描述
uid BIGINT  用户名
gid BIGINT  组ID（无符号）
uid_signed  BIGINT  用户ID为int64 signed（Apple）
gid_signed  BIGINT  默认组ID为int64 signed（Apple）
username    TEXT    用户名
description TEXT    可选的用户描述
directory   TEXT    用户的主目录
shell   TEXT    用户配置的默认外壳
uuid    TEXT    用户的UUID（苹果）
select * from users where uid = 1000
select * from users where username = 'root'
select count(*) from users u, user_groups ug where u.uid = ug.uid
total_seconds   BIGINT  总运行时间秒 


#### fan_speed_sensors
风扇速度。

柱   类型  描述
fan TEXT    风扇号码
name    TEXT    粉丝名称
actual  INTEGER 实际速度
min INTEGER 最低速度
max INTEGER 最大速度
target  INTEGER 目标速度


power_sensors
机器功率（电流，电压，功率等）传感器。

柱   类型  描述
key TEXT    OS X上的SMC密钥
category    TEXT    传感器类别：电流，电压，功率
name    TEXT    电源名称
value   TEXT    功率单位为瓦特
select * from power_sensors where category = 'voltage'

temperature_sensors
机器的温度传感器。

柱   类型  描述
key TEXT    OS X上的SMC密钥
name    TEXT    温度源的名称
celsius DOUBLE  摄氏温度
fahrenheit  DOUBLE  华氏温度