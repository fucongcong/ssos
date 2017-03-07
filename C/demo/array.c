#include <stdio.h>

int a[4];
int b[4] = {2,3,4,5};

// //内部方法
// static int name()
// {
//     printf("static name");
// }

// extern int sex()
// {
//     printf("extern sex");
// }

const int MAX = 4;

int main()
{
    a[0]=20;
    a[1]=345;
    a[2]=700;
    a[3]=22;

    for (int i = 0; i < sizeof(a); i++)
    {
    	printf("a[%d] 地址： %p\n", i, &a[i]);
    }
    printf("%-9d %-9d %-9d %-9d\n", a[0], a[1], a[2], a[3]);
    printf("%-9d %-9d %-9d %-9d\n", b[0], b[1], b[2], b[3]);

    char *names[] = {"Zara Ali","Hina Ali","Nuha Ali","Sara Ali"};
    int i = 0;
    for ( i = 0; i < 4; i++)
    {
       printf("Value of names[%d] = %x\n", i, *names[i] );
    }

    int var1 = 100;
    printf("var1 变量： %d\n", var1);
    printf("var1 变量的地址： %p\n", &var1);

    int *var2 = &var1;

    printf("var2 变量： %d\n", *var2);
    printf("var2 变量的地址： %p\n", &var2);
    printf("var1 变量的地址： %p\n", &*var2);
    printf("var1 变量的地址： %p\n", *&var2);
    return 0;
}

 
