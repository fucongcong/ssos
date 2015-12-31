#include <stdio.h>
#include "function.c"

#define AGE  1
static int YEAR = 20;

struct user
{
    int userId;
    char username[];

}user1;

char hello(char name[])
{
    //外部变量
    extern int X;
    printf("%s \n", name);
    printf("%d \n", X);
    return 'A';
}

struct _zval_struct {
    union {
        long lval;
        double dval;
        struct {
            char *val;
            int len;
        } str;
        // HashTable *ht;
        // zend_object_value obj;
        // zend_ast *ast;
    } value;
    // zend_uint refcount__gc;
    // zend_uchar type;
    // zend_uchar is_ref__gc;
} zval_struct;

int main()
{
    //寄存器变量 放于cpu
    register int OLD = 40;

    hello("hello");
    user1.userId = AGE;
    printf("%d \n", user1.userId);
    printf("%d \n", YEAR);
    printf("%d \n", OLD);

    sex();

    printf("%lu \n", sizeof(zval_struct));
    return 0;
}

int X = 60;