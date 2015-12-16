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
    return 0;
}

int X = 60;