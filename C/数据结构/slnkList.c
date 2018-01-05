#include <stdio.h>
#include <stdlib.h>

#define OK 1
#define ERROR 0

typedef int ElemType;
typedef int Status;

typedef struct LNode
{
    ElemType elem;
    struct LNode *next;
    int len;

} LNode, SlnkList;

Status initList(SlnkList *L) {
    // L = (LNode *)malloc(sizeof(LNode));
    // if (!L) return ERROR;
    L->next = NULL;
    L->elem = 0;
    L->len = 0;

    return OK;
}

Status DestoryList(SlnkList *L) {
    LNode *p, *q;
    p = L;
    int length = L->len;
    for (int i = 0; i < length; i++) {
        q = p->next;
        p->next = q->next;
        free(q);
        L->len = L->len - 1;
    }

    return OK;
}

Status ListInsert(SlnkList *L, int i, ElemType e) {
    LNode *p;
    p = L;
    for (int pos = 1; pos < i; pos++) {
        p = p->next;
    }

    LNode *node;
    node = (LNode *)malloc(sizeof(LNode));
    if (!node) return ERROR;
    node->elem = e;
    node->next = p->next;
    p->next = node;

    L->len = L->len + 1;

    return OK;
}

Status ListDelete(SlnkList *L, int i, ElemType *elem) {
    if (i > L->len || i <= 0) {
        printf("删除失败\n");
        return ERROR;
    }

    LNode *p, *pre;
    p = L;
    for (int pos = 1; pos <= i; pos++) {
        pre = p;
        p = p->next;
    }

    *elem = p->elem;

    //如果是末尾
    if (i == L->len) {
        pre->next = NULL;
    } else {
        pre->next = p->next;
    }
    free(p);

    L->len = L->len - 1;

    return OK;
}

void GetElem(SlnkList *L, int i, ElemType *elem) {

    if (i > L->len) {
        *elem = 0;
        return;
    }

    LNode *p;
    p = L;
    for (int pos = 1; pos <= i; pos++) {
        p = p->next;
    }

    *elem = p->elem;
}

int main(int argc, char const *argv[])
{   
    SlnkList L;
    ElemType elem;
    initList(&L);
    ListInsert(&L, 1, 88);
    printf("当前单链表长度:%d\n", L.len);
    ListInsert(&L, 1, 99);
    ListInsert(&L, 1, 89);
    ListInsert(&L, 2, 82);
    printf("当前单链表长度:%d\n", L.len);

    GetElem(&L, 1, &elem);
    printf("当前值:%d\n", elem);
    GetElem(&L, 2, &elem);
    printf("当前值:%d\n", elem);
    GetElem(&L, 3, &elem);
    printf("当前值:%d\n", elem);
    GetElem(&L, 4, &elem);
    printf("当前值:%d\n", elem);
    GetElem(&L, 5, &elem);
    printf("当前值:%d\n", elem);

    ListDelete(&L, 2, &elem);
    printf("删除第2个值:%d\n", elem);
    GetElem(&L, 2, &elem);
    printf("当前第2个值:%d\n", elem);

    DestoryList(&L);
    printf("当前单链表长度:%d\n", L.len);

    return 0;
}