//
//  fork.c
//  C
//
//  Created by 傅聪聪 on 2017/9/6.
//  Copyright © 2017年 coco.fu. All rights reserved.
//

#include <stdio.h>
#include <sys/wait.h>
#include <unistd.h>
#include <stdlib.h>
#include <signal.h>

int     globvar = 6;

void signalHandle(int signo)
{   
    pid_t   pid;
    while((pid = waitpid(-1, NULL, WNOHANG)) > 0) {
       printf("wait child id exit:%d\n", pid);
    }
}

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
        sleep(3);
        printf("childpid = %ld, glob = %d, var = %d\n", (long)getpid(), globvar,
               var);
        exit(0);
    } else {
        printf("parentpid = %ld, glob = %d, var = %d, childpid = %d\n", (long)getpid(), globvar,
               var, pid);
        
        signal(SIGCHLD, signalHandle);

        printf("parent exit\n");
        for (;;) {}
        //exit(0);
    }
}
