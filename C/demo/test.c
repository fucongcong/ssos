#include <stdio.h>
#include <netinet/in.h>

int main()
{
    unsigned long a = 0x1234;

    printf("当前: %#lx\n", a);

    if ((*(char*)&a) == 0x34) {
        printf("小端序\n");
    } else if ((*(char*)&a) == 0x12) {
        printf("大端序\n");
    }

    printf("host to network short: %#x\n", htons(a));
    printf("host to network long: %#x\n", htonl(a));

    return 0;
}