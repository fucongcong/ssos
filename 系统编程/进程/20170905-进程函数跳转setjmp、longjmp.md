### 进程环境之函数跳转

#### goto函数应该都不陌生吧，它可以在当前函数中实现跳转，但是无法跳入到另一个函数，或者是一个循环、switch结构。

#### 那么如何实现函数之间的跳转呢？setjmp与longjmp闪亮登场。

#### 先看一个简单的demo

```c
    
    #include <stdio.h>
    #include <setjmp.h>

    jmp_buf jmpbuf;
    int i = 0;

    void end()
    {
        if (i < 3) {
            longjmp(jmpbuf, 1);
        }
        
        printf("end\n");
    }

    void action()
    {
        printf("action\n");
        i++;
        end();
    }

    int main()
    {
        if (setjmp(jmpbuf) == 1) {
            printf("jmp to main\n");
        }
        
        action();
    }

```

output 

    action
    jmp to main
    action
    jmp to main
    action
    end

