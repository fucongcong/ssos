#include <stdio.h>
#include <stdlib.h>

#define LIST_INIT_SIZE 10
#define LIST_INCR_SIZE 5

#define OK 1
#define ERROR 0

typedef int ElemType;
typedef int Status;

typedef struct
{
    ElemType *elem;
    int len;
    int listsize;
} SqList;

Status initList(SqList *L) {
    L->elem = (ElemType *)malloc(LIST_INIT_SIZE * sizeof(ElemType));
    if (!L->elem) return ERROR;
    L->len = 0;
    L->listsize = LIST_INIT_SIZE;
    return OK;
}

Status DestoryList(SqList *L) {
    L->len = 0;
    L->listsize = 0;
    free(L->elem);
    return OK;
}

Status ListInsert(SqList *L, int i, ElemType e) {
    if (L->len >= L->listsize) {
        //扩容
        L->elem = (ElemType *)realloc(L->elem, LIST_INCR_SIZE * sizeof(ElemType));
        L->listsize = L->listsize + LIST_INCR_SIZE;
    }

    if (i > L->len + 1 || i < 1) {
        printf("插入失败\n");
        return ERROR;
    }

    //需要把i后面的elem往后移动
    if (i <= L->len) {
        for (int j = L->len + 1; j >= i; j--)
        {
            L->elem[j] = L->elem[j - 1];
        }
    }

    L->elem[i - 1] = e;
    L->len++;

    return OK;
}

Status ListDelete(SqList *L, int i, ElemType *e) {
    if (i - 1 > L->len || i <= 0) {
        printf("删除失败\n");
        return ERROR;
    }

    *e = L->elem[i - 1];
    for (int j = i - 1; j < L->len; j++) {
        L->elem[j] = L->elem[j + 1];
    }

    return OK;
}

void GetElem(SqList *L, int i, ElemType *elem) {
    if (i - 1 > L->len) {
        *elem = 0;
    }

    *elem = L->elem[i - 1];
}

int main(int argc, char const *argv[])
{   
    SqList L;
    ElemType elem;
    initList(&L);
    printf("当前顺序表长度:%d\n", L.len);
    ListInsert(&L, 1, 99);
    ListInsert(&L, 1, 89);
    ListInsert(&L, 2, 82);
    printf("当前顺序表长度:%d\n", L.len);

    GetElem(&L, 1, &elem);
    printf("当前值:%d\n", elem);
    GetElem(&L, 2, &elem);
    printf("当前值:%d\n", elem);
    GetElem(&L, 3, &elem);
    printf("当前值:%d\n", elem);

    ListDelete(&L, 2, &elem);
    printf("删除第2个值:%d\n", elem);
    GetElem(&L, 2, &elem);
    printf("当前第2个值:%d\n", elem);

    DestoryList(&L);
    printf("当前顺序表长度:%d\n", L.len);
    return 0;
}