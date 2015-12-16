#include <stdio.h>
#include <string.h>

int main()
{
    char name[] = "你好，傻哈哈";
    char sex[] = "男";

    //字符串长度
    printf("%lu\n", strlen(name));

    //字符串比较
    printf("%d\n", strcmp(name, sex));

    //字符串替换
    printf("%s\n", strcpy(name, "hehe"));

    //字符串拼接
    printf("%s\n", strcat(name, sex));

    return 0;
}

