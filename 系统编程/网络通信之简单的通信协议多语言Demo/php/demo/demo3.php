<?php 

// struct Members
// {
//     0 need String names;
// }
// struct Company
// {
//     0 need int id;
//     1 need long code;
//     2 need string name;
//     16 optional string addr;
//     4 optional Members members;
// }
// 
// struct User
// {
//     0 need int id;
//     1 optional Company company;
// }

require __DIR__.'/../lib/Co.php';

class Members extends CoStruct {
    public $names;

    public function __construct()
    {
        $this->names = new CoString;
    }

    public function setNames(string $names)
    {
        $this->names->setVal($names);
    }

    public function pack(&$val)
    {
        $this->names->get($val, 0, true);
    }

    public function unpack($data)
    {   
        $this->names = $this->names->set($data, 0);
    }
}

class Company extends CoStruct {
    public $id;
    public $code;
    public $name;
    public $addr;
    public $members;

    public function __construct()
    {
        $this->id = new CoInt;
        $this->code = new CoLong;
        $this->name = new CoString;
        $this->addr = new CoString;
        $this->members = new Members;
    }

    public function setId(int $id)
    {
        $this->id->setVal($id);
    }

    public function setCode($code)
    {
        $this->code->setVal($code);
    }

    public function setName($name)
    {
        $this->name->setVal($name);
    }

    public function setAddr($addr)
    {
        $this->addr->setVal($addr);
    }

    public function setMembers($members)
    {
        $this->members->setVal($members);
    }

    public function pack(&$val)
    {
        $this->id->get($val, 0, true);
        $this->code->get($val, 1, true);
        $this->name->get($val, 2, true);
        $this->addr->get($val, 16, false);
        $this->members->get($val, 4, false);
    }

    public function unpack($data)
    {   
        $this->id = $this->id->set($data, 0);
        $this->code = $this->code->set($data, 1);
        $this->name = $this->name->set($data, 2);
        $this->addr = $this->addr->set($data, 16);
        $this->members = $this->members->set($data, new Members, 4);
    }
}

class User extends CoStruct {
    public $id;
    public $company;

    public function __construct()
    {
        $this->id = new CoInt;
        $this->company = new Company;
    }

    public function setId(int $id)
    {
        $this->id->setVal($id);
    }

    public function setCompany($company)
    {
        $this->company->setVal($company);
    }

    public function pack(&$val)
    {
        $this->id->get($val, 0, true);
        $this->company->get($val, 1, false);
    }

    public function unpack($data)
    {   
        $this->id = $this->id->set($data, 0);
        $this->company = $this->company->set($data, new Company, 1);
    }
}


$data = null;

$members = new Members;
$members->setNames("coco,fucongcong.jason");

$company = new Company;
$company->setId(10);
$company->setCode(1111);
$company->setName('coco');
$company->setAddr('aaaaaaaaaa');
$company->setMembers($members);

$user = new User;
$user->setId(1);
$user->setCompany($company);
$user->pack($data);

echo "压缩后总长度为".strlen($data)."\n";

$unpackData = coPack($data);

$obj = new User;
$obj->unpack($unpackData);
print_r($obj);


$data = [
    'id' => 1,
    'company' => [
        'id' => 10,
        'code' => 1111,
        'name' => 'coco',
        'addr' => 'aaaaaaaaaa',
        'members' => [
            'names' => 'coco,fucongcong.jason'
        ]
    ]
];
echo "json序列化长度为".strlen(json_encode($data))."\n";
