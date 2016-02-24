#####参考GROUP框架的swoole分支的代码
#####nginx配置加入
	#js，css请求合并 安装nginx扩展模块nginx-http-concat，需要重新编译nginx 可以看github项目https://github.com/alibaba/nginx-http-concat
	location /asset/ {
	    concat on;
	    concat_types text/css application/javascript
	    concat_max_files 20;
	}
#####seajs，seajs-combo插件，seajs中的模块必须加id