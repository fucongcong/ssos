#include <stdio.h>

int a[4];
int b[4] = {2,3,4,5};

int main()
{
    a[0]=20;
    a[1]=345;
    a[2]=700;
    a[3]=22;

    printf("%-9d %-9d %-9d %-9d\n", a[0], a[1], a[2], a[3]);
    printf("%-9d %-9d %-9d %-9d\n", b[0], b[1], b[2], b[3]);
}