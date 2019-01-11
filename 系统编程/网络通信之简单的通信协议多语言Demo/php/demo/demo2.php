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
    public function getVal(&$val, $type, $tag, $isNeed = true)
    {   
        if ($this->val != null) {
            if ($tag >= 15) {
                $val .= chr(($type << 4) | 15);
                $val .= chr($tag).$this->val;
            } else {
                $val .= chr(($type << 4) | $tag).$this->val;
            }
        } else {
            if ($isNeed) {
                throw new Exception("缺少必要的参数标记为:{$tag}", 1); 
            }
        }
    }

    public function setVal($data)
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

    public function set($data, $tag)
    {
        if (isset($data[$tag]) && $data[$tag]['type'] == CoType::CO_INT) {
            return $data[$tag]['val'];
        }
    }
}

class CoLong extends DataType
{   
    public function get(&$val, $tag, $isNeed = true)
    {
        $this->getVal($val, CoType::CO_LONG, $tag, $isNeed = true);
    }

    public function set($data, $tag)
    {
        if (isset($data[$tag]) && $data[$tag]['type'] == CoType::CO_LONG) {
            return $data[$tag]['val'];
        }
    }
}

class CoString extends DataType
{   
    public function get(&$val, $tag, $isNeed = true)
    {
        $this->getVal($val, CoType::CO_STRING, $tag, $isNeed = true);
    }

    public function set($data, $tag)
    {
        if (isset($data[$tag]) && $data[$tag]['type'] == CoType::CO_STRING) {
            return $data[$tag]['val'];
        }
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
        $this->id->setVal($id);
    }

    public function setCode($code)
    {
        $this->code->setVal($code);
    }

    public function setName(string $name)
    {
        $this->name->setVal($name);
    }

    public function setAddr(string $addr)
    {
        $this->addr->setVal($addr);
    }

    public function pack(&$val)
    {
        $this->id->get($val, 0, true);
        $this->code->get($val, 1, true);
        $this->name->get($val, 2, true);
        $this->addr->get($val, 16, false);
    }

    public function unpack($data)
    {
        $this->id = $this->id->set($data, 0);
        $this->code = $this->code->set($data, 1);
        $this->name = $this->name->set($data, 2);
        $this->addr = $this->addr->set($data, 16);
    }
}

$data = null;
$obj = new Company;
$obj->setId(10);
$obj->setCode(1111);
$obj->setName('coco');
$obj->setAddr('aaaaaaaaaa');
$obj->pack($data);

echo "压缩后总长度为".strlen($data)."\n";
$len = 0;
$unpackData = [];
while (strlen($data) - $len > 0) {

    if ($len > 0) $data = substr($data, $len);
    $typeAndTag = ord(substr($data, 0, 1));
    $type = $typeAndTag >> 4;

    $tag = $typeAndTag - ($type << 4);
    if ($tag == 15) {
        $data = substr($data, 1);
        $tag = ord(substr($data, 0, 1));
    }

    $data = substr($data, 1);
    $len = unpack("N", substr($data, 0, 4));
    $len = $len[1];

    $data = substr($data, 4);
    $val = unpack("a*", substr($data, 0, $len));
    $val = $val[1];

    $unpackData[$tag] = [
        'type' => $type,
        'tag' => $tag,
        'len' => $len,
        'val' => $val,
    ];
}

$obj = new Company;
$obj->unpack($unpackData);
print_r($obj);


$data = '{"id":10,"code":1111,"name":"coco","addr":"aaaaaaaaaa"}';
echo $data.'总长度为'.strlen($data)."\n";
//总结就是如果字段越多，自定义协议的传输数据的优势越明显。
