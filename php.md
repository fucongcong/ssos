###PHP7内核探索

####下载PHP源码

    git clone https://github.com/php/php-src.git

####目录结构

- build
- main PHP最为核心的文件
- ext PHP扩展
- sapi PHP的解析代理。常见的cli，nginx的fastcgi，php-fpm还有apache的php解析模块，还有一个embed可以用于C/C++中使用zend内核，实现对php的编译
- tests 测试
- travis 持续集成的脚本 
- win32 windows环境下的要用到的
- Zend Zend引擎

####常见的文件

    .c C文件
    .h C的定义文件
    .sh shell脚本
    .mk makefile开发文件,作用与shell类似
    .am makefile开发文件
    .awk 脚本,作用与shell类似
    .phpt 测试文件


####先来开始尝试写我们的第一个扩展，从而了解整个php内核的生命周期
-1.进入ext目录

执行

    ./ext_skel --extname=hello_world

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
    如果是php7 有可能需要升级re2C C的编码器和bison语法分析生成器
    第四步$ ./configure --enable-hello_world


####从最简单的方法开始

````c

    /* 定义一个hello_worl的全局变量 */
    ZEND_DECLARE_MODULE_GLOBALS(hello_world)

    /* ini初始化时我们可以设置参数，需要在php_hello_world.h 中开启ZEND_BEGIN_MODULE_GLOBALS */
    PHP_INI_BEGIN()
        STD_PHP_INI_ENTRY("hello_world.name",      "20", PHP_INI_ALL, OnUpdateLong, hello_world_name, zend_hello_world_globals, hello_world_globals)
        STD_PHP_INI_ENTRY("hello_world.dir", "hello ", PHP_INI_ALL, OnUpdateString, hello_world_dir, zend_hello_world_globals, hello_world_globals)
    PHP_INI_END()


    /* arg是传入的变量名，arg_len是变量的长度，而name和dir是我在ini配置中定义的2个参数 */
    /* {{{ proto void hello_world(string name)
    Greets a user */
    PHP_FUNCTION(hello_world)
    {
        char *arg = NULL;
        size_t arg_len, len;
        zend_string *strg;

        if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &arg, &arg_len) == FAILURE) {
            return;
        }

        int name = INI_INT("hello_world.name");
        const zend_string *dir = INI_STR("hello_world.dir");
        strg = strpprintf(0, "%d %s %s", name, dir, arg);

        RETURN_STR(strg);
    }
    /* }}} */

    /* 初始化全局变量 */
    static void php_hello_world_init_globals(zend_hello_world_globals *hello_world_globals)
    {
        hello_world_globals->hello_world_name = 0;
        hello_world_globals->hello_world_dir = NULL;
    }

    /* }}} */

    /* {{{ PHP_MINIT_FUNCTION
     */
    /* php初始化前做的事 */
    PHP_MINIT_FUNCTION(hello_world)
    {   
        ZEND_INIT_MODULE_GLOBALS(hello_world, php_hello_world_init_globals, NULL);
        REGISTER_INI_ENTRIES();

        return SUCCESS;
    }
    /* }}} */

    /* {{{ PHP_MSHUTDOWN_FUNCTION
     */
    /* php结束整个进程前做的，释放资源 */
    PHP_MSHUTDOWN_FUNCTION(hello_world)
    {
        
        UNREGISTER_INI_ENTRIES();

        return SUCCESS;
    }
    /* }}} */

    /* Remove if there's nothing to do at request start */
    /* {{{ PHP_RINIT_FUNCTION
     */
    /* request前做的 */
    PHP_RINIT_FUNCTION(hello_world)
    {
    #if defined(COMPILE_DL_HELLO_WORLD) && defined(ZTS)
        ZEND_TSRMLS_CACHE_UPDATE();
    #endif
        return SUCCESS;
    }
    /* }}} */

    /* Remove if there's nothing to do at request end */
    /* {{{ PHP_RSHUTDOWN_FUNCTION
     */
    /* request结束时做的 */
    PHP_RSHUTDOWN_FUNCTION(hello_world)
    {
        return SUCCESS;
    }
    
````
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

####第一个扩展就完成了，对整个php的生命周期也有了初步的了解   

    执行./sapi/cli/php -r 'echo hello_world('cc');'


    结果 20 hello cc

##### [鸟哥的早期文章,用C/C++扩展你的PHP](http://www.laruence.com/2009/04/28/719.html)
###了解完一个简单的扩展之后，就正式开始我们的内核探索之旅。




//常用的ssl加密

openssl_public_decrypt()

$name = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);

####优化小点
#####可以用empty或者isset判断变量时，尽量少用is_null（性能太差，差几十倍）等方法

// 开启coredump
ulimit -c unlimited
//
ulimit -c 0
