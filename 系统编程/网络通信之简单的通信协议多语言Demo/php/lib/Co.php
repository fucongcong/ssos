<?php 

function coPack($data)
{
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

    return $unpackData;
}

class CoType
{
    const CO_INT = 1;
    const CO_LONG = 2;
    const CO_STRING = 3;
    const CO_STRUCT = 4;
}

class DataType 
{   
    public function getVal(&$val, $type, $tag, $isNeed = true)
    {   
        if (!isset($this->val)) {
            $this->val = null;
        }
        
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
        $this->getVal($val, CoType::CO_INT, $tag, $isNeed);
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
        $this->getVal($val, CoType::CO_LONG, $tag, $isNeed);
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
        $this->getVal($val, CoType::CO_STRING, $tag, $isNeed);
    }

    public function set($data, $tag)
    {
        if (isset($data[$tag]) && $data[$tag]['type'] == CoType::CO_STRING) {
            return $data[$tag]['val'];
        }
    }
}

class CoStruct extends DataType
{   
    public function get(&$val, $tag, $isNeed = true)
    {
        $this->getVal($val, CoType::CO_STRUCT, $tag, $isNeed);
    }

    public function setVal($data)
    {   
        $data->pack($this->val);
        $bodyLen = strlen($this->val);
        $head = pack("N", $bodyLen);
        $this->val = $head.$this->val;
    }

    public function set($data, $struct, $tag)
    {
        if (isset($data[$tag]) && $data[$tag]['type'] == CoType::CO_STRUCT) {
            $unpackData = coPack($data[$tag]['val']);
            $struct->unpack($unpackData);

            return $struct;
        }
    }
}