内核运算操作方法

int add_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);                 /*  +  */
int sub_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);                 /*  -  */
int mul_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);                 /*  *  */
int div_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);                 /*  /  */
int mod_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);                 /*  %  */
int concat_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);              /*  .  */
int bitwise_or_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);          /*  |  */
int bitwise_and_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);         /*  &  */
int bitwise_xor_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);         /*  ^  */
int shift_left_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);          /*  << */
int shift_right_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);         /*  >> */
int boolean_xor_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);         /* xor */
int is_equal_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);            /*  == */
int is_not_equal_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);        /*  != */
int is_identical_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);        /* === */
int is_not_identical_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);    /* !== */
int is_smaller_function(zval *result, zval *op1, zval *op2 TSRMLS_DC);          /*  <  */
int is_smaller_or_equal_function(zval *result, zval *op1, zval *op2 TSRMLS_DC); /*  <= */


加法
zval  * a,* b,* res;
MAKE_STD_ZVAL(a);
MAKE_STD_ZVAL(b);
MAKE_STD_ZVAL(res);

ZVAL_DOUBLE(a, 3.68);
ZVAL_STRING(b, "20", 1);

add_function(res,  a, b TSRMLS_CC);

php_printf("%Z\n", res);