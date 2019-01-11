<?php 

class CoTrans
{
    protected $coFilePath;

    public function __construct($coFilePath)
    {
        $this->coFilePath = $coFilePath;
    }

    public function transTo($toPath)
    {   
        if (!file_exists($this->coFilePath)) {
            exit("该文件不存在");
        }
        $data = file_get_contents($this->coFilePath);
        var_dump($data);
        preg_match('/service(?P<service>.\S*)/x', $data, $matches);
        var_dump($matches);
    }
}

class CoType
{
    const CO_INT = 1;
    const CO_LONG = 2;
    const CO_STRING = 3;
}


$co = new CoTrans("../company.co");
$co->transTo("");