//
//  fork.c
//  C
//
//  Created by 傅聪聪 on 2017/9/6.
//  Copyright © 2017年 coco.fu. All rights reserved.
//

#include <stdio.h>
#include <unistd.h>

int     globvar = 6;

int
main(void)
{
    int     var;
    pid_t   pid;
    
    var = 88;

    if ((pid = fork()) < 0) {
        perror("fork error");
    } else if (pid == 0) {
        globvar++;
        var++;
        printf("childpid = %ld, glob = %d, var = %d\n", (long)getpid(), globvar,
               var);
    } else {
        printf("parentpid = %ld, glob = %d, var = %d\n", (long)getpid(), globvar,
               var);
    }
    
    exit(0);
}
