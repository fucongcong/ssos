#include<stdio.h>
#include<stdlib.h>
#include<time.h>
#include<string.h>
#define MAXNUM 2000 

int filenum;
int filenumtemp;
int filenumend;

void CreatFile()
{
    FILE *f;
    f = fopen("test.txt", "w+");
    srand((unsigned)time(NULL));
    for (int i = 0; i < 10000; ++i)
    {
        int temp = rand() % 100; //产生0-100的随机数
        fprintf(f, "%d\n", temp);

    }
    fclose(f);
}

void merge(int num[], int start, int mid, int end)
{
    int n1 = mid - start + 1;
    int n2 = end - mid;
    int *left, *right;
    left = (int*)malloc(n1 * sizeof(int));
    right = (int*)malloc(n2 * sizeof(int));
    int i, j, k;

    for (i = 0; i < n1; i++)
        left[i] = num[start + i];
    for (j = 0; j < n2; j++)
        right[j] = num[mid + 1 + j];

    i = j = 0;
    k = start;
    while (i < n1 && j < n2)
        if (left[i] < right[j])
            num[k++] = left[i++];
        else
            num[k++] = right[j++];

    while (i < n1)
        num[k++] = left[i++];

    while (j < n2)
        num[k++] = right[j++];

    free(left);
    free(right);
}

void merge_sort(int num[], int start, int end)
{
    int mid;
    if (start < end)
    {
        mid = (start + end) / 2;

        merge_sort(num, start, mid);
        merge_sort(num, mid + 1, end);
        merge(num, start, mid, end);
    }
}

void MergeFile()
{
    filenumtemp = 0;
    while (filenum != 1)
    {
        while (filenumtemp < filenum)
        {
            if ((filenum - filenumtemp) == 1)
            {
                FILE *f1, *f;

                char filename1[10] = { "" };
                filenumtemp++;
                filename1[0] = filenumtemp + 48;
                strcat(filename1, ".txt");
                f1 = fopen(filename1, "r");

                filenumend++;
                char filename[10] = { "" };
                filename[0] = filenumend + 48;
                strcat(filename, "temp.txt");
                f = fopen(filename, "w+");

                int num1;
                while (fscanf(f1, "%d", &num1) != EOF)
                {
                    fprintf(f, "%d\n", num1);
                }
                fclose(f1);
                fclose(f);
            }
            else
            {
                FILE *f1, *f2, *f;
                char filename1[10] = { "" };
                filenumtemp++;
                filename1[0] = filenumtemp + 48;
                strcat(filename1, ".txt");
                f1 = fopen(filename1, "r");

                char filename2[10] = { "" };
                filenumtemp++;
                filename2[0] = filenumtemp + 48;
                strcat(filename2, ".txt");
                f2 = fopen(filename2, "r");

                filenumend++;
                char filename[10] = { "" };
                filename[0] = filenumend + 48;
                strcat(filename, "temp.txt");
                f = fopen(filename, "w+");

                int temp;
                int count = 0;
                int num1, num2;
                fscanf(f1, "%d", &num1);
                fscanf(f2, "%d", &num2);
                while(1)
                {
                    if (num1 < num2)
                    {
                        fprintf(f, "%d\n", num1);
                        if (fscanf(f1, "%d", &num1) == EOF)
                        {
                            fprintf(f, "%d\n", num2);
                            while (fscanf(f2, "%d", &num2) != EOF)
                            {
                                fprintf(f, "%d\n", num2);
                            }
                            break;
                        }
                    }
                    else
                    {
                        fprintf(f, "%d\n", num2);
                        if (fscanf(f2, "%d", &num2) == EOF)
                        {
                            fprintf(f, "%d\n", num1);
                            while (fscanf(f1, "%d", &num1) != EOF)
                            {
                                fprintf(f, "%d\n", num1);
                            }
                            break;
                        }
                    }
                }
                fclose(f1);
                fclose(f2);
                fclose(f);
            }

            char filename1[10] = { "" };
            char filename2[10] = { "" };
            filename1[0] = filenumend + 48;
            filename2[0] = filenumend + 48;
            strcat(filename1, "temp.txt");
            strcat(filename2, ".txt");

            char filename3[10] = { "" };
            char filename4[10] = { "" };
            filename3[0] = filenumend * 2 - 1 + 48;
            filename4[0] = filenumend * 2 + 48;
            strcat(filename3, ".txt");
            strcat(filename4, ".txt");

            int r1 = remove(filename3);
            int r2 = remove(filename4);
            printf("r1=%d r2=%d\n", r1, r2);
            rename(filename1, filename2);

            //printf("filenum=%d filenumtemp=%d filenumend=%d\n", filenum, filenumtemp, filenumend);
            if (filenumtemp == filenum&&filenum != 1)
            {
                filenum = filenumend;
                filenumtemp = 0;
                filenumend = 0;
            }

        }
    }
    char filename1[20] = { "" };
    char filename2[20] = { "" };
    filename1[0] = 1 + 48;
    strcat(filename1, ".txt");
    strcat(filename2, "test_sort.txt");
    rename(filename1, filename2);
    printf("排序完成，有序序列保存在：test_sort.txt文件中\n");
}

void CreatTempFile(int temp[], int count)
{
    FILE *f;
    char filename[10] = { "" };
    filename[0] = filenum + 48;
    strcat(filename, ".txt");
    f = fopen(filename, "w+");
    for (int i = 0; i < count; ++i)
    {
        fprintf(f, "%d\n", temp[i]);
    }
    fclose(f);
}

void SortFile()
{
    FILE *f;
    f = fopen("test.txt", "r");
    int *temp;
    temp = (int *)malloc(MAXNUM * sizeof(int));
    char tempchar;
    int count = 0;
    while (fscanf(f, "%d", &temp[count])!=EOF)
    {
        count++;
        if (count == MAXNUM)
        {
            filenum++;
            merge_sort(temp, 0, count - 1);
            CreatTempFile(temp, count);
            count = 0;
        }
    }
    if (count != 0)
    {
        filenum++;
        CreatTempFile(temp, count);
        count = 0;
    }
    fclose(f);
    free(temp);
}

int main()
{

    CreatFile(); //生成10000个随机数存储在test.txt文件中
    SortFile(); //初次切割并排序为有序文件
    MergeFile(); //对文件进行归并排序

    return 0;
}