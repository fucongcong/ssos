#### ELK(ElasticSearch+Logstash+ Kibana)搭建实时日志分析平台

#### 安装logstash

- 下载安装包
- 执行 bin/logstash -e 'input { stdin { } } output { stdout { codec => rubydebug } }'  
- 输入任意文字 即可看到logstash处理之后的数据

##### Logstash的作用
它作为日志处理的第一道工具，用于处理接收到的日志，并按一定的格式统一的转发到ElasticSearch

##### 官方配置示例
https://www.elastic.co/guide/en/logstash/current/config-examples.html

#### linux 安装Logstash.(具体见官方文档)
- 下载rpm包或者zip包
- 配置conf
- 使用bin/system-install工具完成服务控制。

#### 安装ElasticSearch
启动: bin/elasticsearch -d 
