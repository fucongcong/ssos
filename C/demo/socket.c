#include <sys/socket.h>
#include <stdio.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <string.h>

int main()
{
    int fd = socket(AF_INET, SOCK_STREAM, 0);
    if(-1 == fd)
    {
      perror("can not create socket");
      exit(EXIT_FAILURE);
    }

    struct sockaddr_in serv_addr;
    char host[] = "127.0.0.1";
    short port = 1234;
    short buf_size = 2;
    char eof = "-";

    memset(&serv_addr, 0, sizeof(serv_addr));
    serv_addr.sin_family = AF_INET;
    if (!inet_aton(host, &serv_addr.sin_addr.s_addr)) {
        perror("bad address");
        exit(EXIT_FAILURE);
    }
    serv_addr.sin_port = htons(port);
    if(-1 == bind(fd, (struct sockaddr*)&serv_addr, sizeof(serv_addr))) {
        perror("bind address fail");
        close(fd);
        exit(EXIT_FAILURE);
    }

    if(-1 == listen(fd, 10))
    {
        perror("error listen failed");
        close(fd);
        exit(EXIT_FAILURE);
    }

    for(;;)
    {
        int clientFd = accept(fd, NULL, NULL);

        if(0 > clientFd)
        {
            perror("error accept failed");
            close(fd);
            exit(EXIT_FAILURE);
        }

        int res = 1;
        char buffer[buf_size];  //缓冲区
        char recvBuff[1024000];

        //char buf[buf_size];
        while(res) {
            int strLen = recv(clientFd, buffer, buf_size, 0);  //接收客户端发来的数据
            strcat(recvBuff, buffer);
            if (strLen < sizeof(buffer)) {
                break;
            } else {
                res = 1;
            }
        }
        
        printf("%s\n", recvBuff);
        memset(&recvBuff, 0, sizeof(recvBuff));

        shutdown(clientFd, SHUT_RDWR);
        close(clientFd);
        //send(clientFd, buffer, strLen, 0);  //将数据原样返回
    }

    close(fd);
    return 0;
}