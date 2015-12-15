#include <stdio.h>

struct user
{
    int userId;
    char *username;
}user1;

int main()
{
    user1.userId = 1;
    printf("%d \n", user1.userId);
}