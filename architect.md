####前端架构

    html5 js css分层（传统）
    以js,css框架seajsj，sass.less等等加以控制，适合项目前期。
    将js拆分成MVC模式，适用于项目壮大之后。

####常见的前端工具：

    样式可视化文档holorgram
    第一阶段： 规范与设计
    定制必要的开发规范
    定制项目的组件化拆分方案
    第二阶段： 技术选型
    选择前端组件化框架（seajs, requirejs, ...）
    选择前端基础库（jquery, tangram, ...）
    选择模板语言（twig, smarty, ...）
    选择模板插件（xss修复）
    第三阶段： 自动化与拆分
    选择或开发自动化工具（打包，压缩，校验）
    将系统拆分为几个子系统，以便大团队并行开发
    适当调整框架以适应工具产出
    第四阶段： 性能优化
    小心剔除已下线的功能
    优化http请求
    适当调整自动化工具以适应性能优化
    使用架构级优化方案：BigPipe、BigRender等

####服务器端架构

    轻量级，直接使用MVC框架即可（sy2,laravel,thinkphp,ci）+ mysql
    中型，MVC框架+ mysql + key-value缓存（redis，memcache）
    大型  MVC框架+ mysql + key-value缓存（redis，memcache）+ 集群
    静态资源cdn
    压缩合并js，css，图片请求
    DNS集群
    服务器集群（nginx，apache，nginx做反向代理分发到apache）(请看我的Group框架)
    使用swoole搭建服务器，处理同步异步请求，结合传统服务器集群做数据处理，和数据库和缓存服务器通信。
    数据库集群 mysql的（master slave 读写分离）(请看我的Group框架)
    缓存服务器集群redis（master slave）
    定时脚本处理业务数据(请看我的Group框架)
    服务器配置优化(请看配置优化篇)
    sql优化(语句的调整与表结构的设计优化，分区和分表往后放)
    消息队列(请看我的Group框架)
    
[服务器反代+负载配置）](https://github.com/fucongcong/Group/blob/master/doc/nginx_apache%E8%B4%9F%E8%BD%BD%2B%E5%8F%8D%E5%90%91%E4%BB%A3%E7%90%86.txt)
####监控：http://tpm.oneapm.com/
####压力测试：ab
####备份：热备 冷备
####安全测试：

阿里的slb，rds
