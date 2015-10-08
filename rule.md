启始符号　/表达式/
\t  匹配tab建
\n　匹配换行回车
\d　数字
\D 非数字
| 或
[] 匹配[]内部所有原子
[^] 匹配[]内部所有原子之外的元素
\w 匹配数字，字母，下划线　等于　[a-zA-Z0-9_]
\W 匹配数字，字母，下划线之外的元素　[＾a-zA-Z0-9_]
\s 匹配任何空白字符，包括空格、制表符、换页符等。与 [ \f\n\r\t\v] 等效。
\S 与\s相反

a{1}  匹配 a
a{2}  匹配　aa
a{1,2} 匹配　a aa
a {2,} 匹配　aa aaa aaaa...
*  等于{0,}
+ 等于{1,}
? 等于{0,1}

结尾　/i 忽略大小写
    　　/x 忽略空白

img图片匹配src
$string = "aaa<img title = \"aa\" src=\"http://text.jpg\" alt=\"xxx\" />hahahah3212aaa<img title = \"aa\" src=\"http://text2.jpg\" alt=\"xxx\" />hahahah3212";

preg_match_all('/<img.*?src=[\'"](.*?)[\'"].*?>/', $string, $url);
print_r($url);