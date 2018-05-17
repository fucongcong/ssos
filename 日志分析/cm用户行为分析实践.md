#### 方案
异步httpserver+logstash+elasticsearch+apiserver+ui界面展示

#### 需要记录的数据:
- ip 
- 时间 
- 访问页面地址 
- referrer
- agent
- uuid（用户识别码）
- 设备(pc或mobile)

#### 按当日分析出ip
GET /logstash-2013.12.11/_search
{
    "size" : 0,
    "aggs" : { 
        "clientips" : { 
            "terms" : { 
              "field" : "clientip.keyword"
            }
        }
    }
}

#### 得到ip后，根据ip获取用户日志路径,按时间排序
GET /logstash-2018.01.09/_search
{   
    "query": {
        "bool": {
          "must": [
            {
              "match": 
                {
                "clientip.keyword": "192.168.0.156"
                }
            }
          ],
          "must_not": [
            {
              "prefix": {
                "request.keyword": {
                  "value": "/assets/"
                }
              }
            },
            {
              "prefix": {
                "request.keyword": {
                  "value": "/fonts/"
                }
              }
            }
          ]
        }
    },
    "sort": { "@timestamp": { "order": "desc" }}
}
