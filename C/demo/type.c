#include <stdio.h>

int a = 1;
char b = 'A';
short c = 1;
double d = 1.12;
float e = 1.13;
char f[] = "hello world";
long g= 1;

int main()
{
    printf("%d \n", a);
    printf("%c \n", b);
    printf("%d \n", c);
    printf("%f \n", d);
    printf("%f \n", e);
    printf("%s \n", f);

    printf("字节大小 \n");
    printf("%lu \n", sizeof(a));
    printf("%lu \n", sizeof(b));
    printf("%lu \n", sizeof(c));
    printf("%lu \n", sizeof(d));
    printf("%lu \n", sizeof(e));
    printf("%lu \n", sizeof(f));
    printf("%lu \n", sizeof(g));
    return 0;
}
