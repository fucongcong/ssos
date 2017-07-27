#### git忽略文件不起作用
git rm -r --cached .
git add .
git commit -m 'update .gitignore'

####首先每个版本都需要打tag

    git tag -a 'v1.0' -m 'v1.0'

####当版本为v1.1时

    git diff  --name-status v1.0 master > diff-v1.1

此时会生成与v1.0变更的diff-v1.1文件：
如

    A diff-1.1
    D index.html
    M src/Custom/WebBundle/Controller/UserController.php
    M src/Topxia/Service/User/Impl/UserServiceImpl.php
    M src/Topxia/WebBundle/Handler/AuthenticationProvider.php

A表示新增，D表示删除，M表示更新

####打包diff-v1.1文件列表中得文件。
php build.php -f diff-v1.1

build.php

    <?php

    $args = getopt('f:');

    if (!isset($args['f'])) {

        echo "请输入差异文件名称\n";
        die;
    }

    $file = $args['f'];
    $readFile = "MAIN_".$file;
    $myfile = fopen($file , "r") or die("Unable to open file!");
    $data = "";
    echo "开始制作压缩包...\n";
    while(!feof($myfile)) {

        $info = doTrim(fgets($myfile));

        $status = substr($info, 0, 1);

        $fileName = substr($info, 2);
        switch ($status) {
            case 'D':
                break;
            case 'M':
            case 'A':
                $data.= $fileName." ";
                break;
            default:
                break;
        }
    }
    fclose($myfile);
    $command = "zip {$readFile}.zip {$data} {$file}";
    exec($command);
    echo "压缩包制作完成！\n";

    function doTrim($data)
    {
        $data=trim($data);
        $data=str_replace(" ","",$data);
        $data=str_replace('\n','',$data);
        $data=str_replace('\r','',$data);
        $data=str_replace('\t','',$data);

        return $data;
    }


####上传zip包即可。


####待更新服务器需要下载更新包，执行更新脚本。


update.php

    <?php

    $command = "unzip MAIN_diff-v1.1.zip -d build/";
    exec($command);

    $file = "build/diff-v1.1";
    $myfile = fopen($file , "r") or die("缺少更新列表文件");
    echo "";

    while(!feof($myfile)) {

        $info = doTrim(fgets($myfile));

        $status = substr($info, 0, 1);

        $fileName = substr($info, 2);
        switch ($status) {
            case 'D':
                //删除相应文件
                break;
            case 'M':
                //覆盖文件
                break;
            case 'A':
                //新增文件
                break;
            default:
                break;
        }
    }
    fclose($myfile);

