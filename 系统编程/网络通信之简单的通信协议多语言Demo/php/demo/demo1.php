<?php 

// struct Company
// {
//     0 need int id;
//     1 need long code;
//     2 need string name;
//     3 optional string addr;
// }
// 
class CoType
{
    const CO_INT = 1;
    const CO_LONG = 2;
    const CO_STRING = 3;
}

class DataType 
{   
    public $val = null;

    public function getVal(&$val, $type, $tag, $isNeed = true)
    {   
        if ($this->val != null) {
            $val .= chr($type).chr($tag).$this->val;
        } else {
            if ($isNeed) {
                throw new Exception("缺少必要的参数标记为:{$tag}", 1); 
            }
        }
    }

    public function set($data)
    {
        $this->val =  pack("a*", $data);
        $bodyLen = strlen($this->val);
        $head = pack("N", $bodyLen);
        $this->val = $head.$this->val;
    }
}

class CoInt extends DataType
{   
    public function get(&$val, $tag, $isNeed = true)
    {
        $this->getVal($val, CoType::CO_INT, $tag, $isNeed = true);
    }
}

class CoLong extends DataType
{   
    public function get(&$val, $tag, $isNeed = true)
    {
        $this->getVal($val, CoType::CO_LONG, $tag, $isNeed = true);
    }
}

class CoString extends DataType
{   
    public function get(&$val, $tag, $isNeed = true)
    {
        $this->getVal($val, CoType::CO_STRING, $tag, $isNeed = true);
    }
}

class Company {
    public $id;
    public $code;
    public $name;
    public $addr;

    public function __construct()
    {
        $this->id = new CoInt;
        $this->code = new CoLong;
        $this->name = new CoString;
        $this->addr = new CoString;
    }

    public function setId(int $id)
    {
        $this->id->set($id);
    }

    public function setCode($code)
    {
        $this->code->set($code);
    }

    public function setName(string $name)
    {
        $this->name->set($name);
    }

    public function setAddr(string $addr)
    {
        $this->addr->set($addr);
    }

    public function returnVal(&$val)
    {
        $this->id->get($val, 0, true);
        $this->code->get($val, 1, true);
        $this->name->get($val, 2, true);
        $this->addr->get($val, 3, false);
    }
}

$data = null;
$obj = new Company;
$obj->setId(10);
$obj->setCode(1111);
$obj->setName('coco');
$obj->setAddr('aaaaaaaaaa');
$obj->returnVal($data);

echo "压缩后总长度为".strlen($data)."\n";

$len = 0;
while (strlen($data) - $len > 0) {
   //////
   $data = substr($data, $len);
   $type = ord(substr($data, 0, 1));
   echo "type:".$type."\n";

   $data = substr($data, 1);
   $tag = ord(substr($data, 0, 1));
   echo "tag:{$tag}"."\n";

   $data = substr($data, 1);
   $len = unpack("N", substr($data, 0, 4));
   $len = $len[1];
   echo "len:".$len[1]."\n";

   $data = substr($data, 4);
   $val = unpack("a*", substr($data, 0, $len));
   echo "val:".$val[1]."\n";
}

$data = '{"id":10,"code":1111,"name":"coco","addr":"aaaaaaaaaa"}';
echo $data.'总长度为'.strlen($data)."\n";
//总结就是如果字段越多，自定义协议的传输数据的优势越明显。
