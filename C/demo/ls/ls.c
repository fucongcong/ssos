#include <stdio.h>
#include <dirent.h>
#include <stdlib.h>

int main(int argc, char *argv[])
{
    DIR *dp;
    struct dirent *dirp;

    dirp = readdir(dp);
    while(dirp != NULL)
        printf("%s\n", dirp->d_name);

    closedir(dp);
    exit(0);
}

