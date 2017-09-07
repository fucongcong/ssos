//
//  socket.c
//  C
//
//  Created by 傅聪聪 on 2017/7/31.
//  Copyright © 2017年 coco.fu. All rights reserved.
//

#include "socket.h"
#include <sys/socket.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <string.h>
#include <fcntl.h>

#define BUFSIZE 2
#define FD_SETSIZE 512

static int make_socket_non_blocking(int sfd)
{
    int flags, s;
    flags = fcntl(sfd, F_GETFL,0);
    if(flags == -1)
    {
        perror("fcntl");
        return-1;
    }
    
    flags|= O_NONBLOCK;
    s =fcntl(sfd, F_SETFL, flags);
    if(s ==-1)
    {
        perror("fcntl");
        return-1;
    }
    return 0;
}

int main()
{
    int fd = socket(AF_INET, SOCK_STREAM, 0);
    if(-1 == fd)
    {
        perror("can not create socket");
        exit(EXIT_FAILURE);
    }
    
    struct sockaddr_in serv_addr,client_addr;
    char host[] = "127.0.0.1";
    short port = 1234;
    int res = 1;
    char clientIp[20],buffer[BUFSIZE],recvBuff[1024000];  //缓冲区
    char close_exec[] = "close";
    
    bzero(&serv_addr, sizeof(serv_addr));
    serv_addr.sin_family = AF_INET;
    // if (!inet_aton(host, &serv_addr.sin_addr.s_addr)) {
    //     perror("bad address");
    //     exit(EXIT_FAILURE);
    // }
    if (inet_pton(AF_INET, host, &serv_addr.sin_addr) <= 0) {
        perror("bad address");
        exit(EXIT_FAILURE);
    }
    
    serv_addr.sin_port = htons(port);
    if(-1 == bind(fd, (struct sockaddr*)&serv_addr, sizeof(serv_addr))) {
        perror("bind address fail");
        close(fd);
        exit(EXIT_FAILURE);
    }
    
    //make_socket_non_blocking(fd);
    
    if(-1 == listen(fd, 256))
    {
        perror("error listen failed");
        close(fd);
        exit(EXIT_FAILURE);
    }
    
    fd_set rfds;
    int maxfd = fd;
    FD_ZERO(&rfds);
    FD_SET(fd, &rfds);
    struct timeval timeout = {5,0};
    int client[FD_SETSIZE],maxIndex = 0, i;
    
    while (1) {
        select(maxfd + 1, &rfds, NULL, NULL, NULL);
        if (FD_ISSET(fd, &rfds)) {
            size_t len = sizeof(client_addr);
            int clientFd = accept(fd, (struct sockaddr*)&client_addr, &len);
            
            if(0 > clientFd)
            {
                perror("error accept failed");
                close(fd);
                exit(EXIT_FAILURE);
            }
            
            printf("connection from %s, port %d\n",
                   inet_ntop(AF_INET, &client_addr.sin_addr, clientIp, sizeof(clientIp)),
                   ntohs(client_addr.sin_port));
            //丢入fd集合
            
            for (i = 0; i < FD_SETSIZE; i++) {
                if (client[i] <= 0) {
                    client[i] = clientFd;
                    break;
                }
            }
            if (i == FD_SETSIZE) {
                shutdown(clientFd, SHUT_RDWR);
                close(clientFd);
            } else {
                FD_SET(clientFd, &rfds);
                if (clientFd > maxfd)
                    maxfd = clientFd;
                if (i > maxIndex)
                    maxIndex = i;
            }
            
            for (i = 0; i <= maxIndex; i++) {
                int clientFd = client[i];
                if (clientFd <= 0) {
                    continue;
                }
                //开一个线程或者进程去做下面任务
                if (FD_ISSET(clientFd, &rfds)) {
                    while(res) {
                        ssize_t strLength = recv(clientFd, buffer, sizeof(buffer), 0);  //接收客户端发来的数据
                        
                        if (strLength < 0) {
                            continue;
                        }
                        
                        if (strLength < sizeof(buffer)) {
                            if (strLength > 0) {
                                char dest[BUFSIZE];
                                bzero(&dest, sizeof(dest));
                                strncpy(dest, buffer, strLength);
                                strcat(recvBuff, dest);
                            }
                            break;
                        } else {
                            strcat(recvBuff, buffer);
                            res = 1;
                        }
                    }
                    
                    printf("res:%s\n", recvBuff);
                    shutdown(clientFd, SHUT_RDWR);
                    close(clientFd);
                    FD_CLR(clientFd, &rfds);
                    client[i] = -1;
                    if (strcmp(recvBuff, close_exec) == 0) {
                        close(fd);
                        return 0;
                    }
                    bzero(&recvBuff, sizeof(recvBuff));
                    //send(clientFd, buffer, strLen, 0);  //将数据原样返回
                }
            }
        }
    }
    
    close(fd);
    return 0;
}
