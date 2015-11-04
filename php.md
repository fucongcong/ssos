###PHP内核探索

####下载PHP源码

    git clone https://github.com/php/php-src.git

####目录结构

- build
- ext PHP扩展
- tests 测试
- Zend Zend引擎

####常见的文件

    .c C文件
    .h C的定义文件
    .sh shell脚本
    .mk makefile开发文件,作用与shell类似
    .am makefile开发文件
    .awk 脚本,作用与shell类似
    .phpt 测试文件








//常用的ssl加密
openssl_public_decrypt()
$name = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);