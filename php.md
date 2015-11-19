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


####开始尝试写我们的第一个扩展
-1.进入ext目录

执行

    ./ext_skel hello_world

将会出现

    Creating directory hello_world
    Creating basic files: config.m4 config.w32 .gitignore hello_world.c php_hello_world.h CREDITS EXPERIMENTAL tests/001.phpt hello_world.php [done].

    To use your new extension, you will have to execute the following steps:

    1.  $ cd ..
    2.  $ vi ext/hello_world/config.m4
    3.  $ ./buildconf
    4.  $ ./configure --[with|enable]-hello_world
    5.  $ make
    6.  $ ./sapi/cli/php -f ext/hello_world/hello_world.php
    7.  $ vi ext/hello_world/hello_world.c
    8.  $ make

    Repeat steps 3-6 until you are satisfied with ext/hello_world/config.m4 and
    step 6 confirms that your module is compiled into PHP. Then, start writing
    code and repeat the last two steps as often as necessary.

-2.按着上面步骤执行
    第四步$ ./configure --enable-hello_world


####从最简单的方法开始

    /* {{{ proto void hello_world(string name)
    Greets a user */
    PHP_FUNCTION(hello_world)
    {
        char *name;
        int name_len;

        if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s", &name, &name_len) == FAILURE) {
            return;
        }

        php_printf("Hello %s!", name);

        RETURN_TRUE;
    }
    /* }}} */

    定义了一个hello_world方法，定义了 name，和name_len两个变量，然后通过zend_parse_parameters()拿取用户传入的变量值，“s”表示是一个字符串，后面两个引用，就是把指针传递给我们之前定义的两个变量中去，最后判断如果传递参数有误，就返回一个NULL值。

zend_parse_parameters() 类型说明符

修饰符|类型|描述
:---------------|:---------------|:---------------
a|array|数组
b|zend_bool|boolean
l|long|integer 整型
d|double|float 浮点型
s|char*|二进制安全字符串，当为s时，需要在后面定义length长度
r|Resource|资源
h|HashTable*|数组的哈希表
o|Object instance|对象
z|Non-specific zval|任意类型

####第一个扩展就完成了



//常用的ssl加密

openssl_public_decrypt()

$name = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

####优化小点
#####可以用empty或者isset判断变量时，尽量少用is_null（性能太差，差几十倍）等方法