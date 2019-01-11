<?php 

require __DIR__.'/../lib/Co.php';

class Members extends CoStruct {
    public $names;
    public $sexs;
    public $work = false;

    public function __clone()
    {
        $this->names = clone $this->names;
    }

    public function __construct()
    {
        $this->names = new CoList(new CoString);
        // $this->sexs = new CoMap(new CoString, new CoString);
        // $this->work = new CoBool;
    }

    public function setNames($names)
    {
        $this->names->setVal($names);
    }

    public function setSexs($sexs)
    {
        $this->sexs->setVal($sexs);
    }

    public function setWork($work)
    {
        $this->work->setVal($work);
    }

    public function pack(&$val)
    {
        $this->names->get($val, 0, true);
        // $this->sexs->get($val, 1, false);
        // $this->work->get($val, 2, false);
    }

    public function unpack($data)
    {
        $this->names = $this->names->getUnpackData($data, 0);
        // $this->sexs = $this->sexs->getUnpackData($data, 1);
        // $this->work = $this->work->getUnpackData($data, 2);
    }
}

class Company extends CoStruct {

    public $id;

    public $code;

    public $name;

    public $addr;

    public $members;

    public $memberLists;

    public function __clone()
    {
        $this->id = clone $this->id;
        $this->code = clone $this->code;
        $this->name = clone $this->name;
        $this->addr = clone $this->addr;
        $this->members = clone $this->members;
        $this->memberLists = clone $this->memberLists;
    }

    public function __construct()
    {
        $this->id = new CoInt;
        $this->code = new CoLong;
        $this->name = new CoString;
        $this->addr = new CoString;
        $this->members = new Members;
        $this->memberLists = new CoList(new Members);
    }

    public function setId($id)
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

    public function setMemberLists($memberLists)
    {
        $this->memberLists->setVal($memberLists);
    }

    public function pack(&$val)
    {
        $this->id->get($val, 0, true);
        $this->code->get($val, 1, true);
        $this->name->get($val, 2, true);
        $this->addr->get($val, 16, false);
        $this->members->get($val, 4, false);
        $this->memberLists->get($val, 5, false); 
    }

    public function unpack($data)
    {   
        $this->id = $this->id->getUnpackData($data, 0);
        $this->code = $this->code->getUnpackData($data, 1);
        $this->name = $this->name->getUnpackData($data, 2);
        $this->addr = $this->addr->getUnpackData($data, 16);
        $this->members = $this->members->getUnpackData($data, new Members, 4);
        $this->memberLists = $this->memberLists->getUnpackData($data, 5);
    }
}

class User extends CoStruct {
    public $id;
    public $company;

    public function __clone()
    {
        $this->id = clone $this->id;
        $this->company = clone $this->company;
    }

    public function __construct()
    {
        $this->id = new CoInt;
        $this->company = new Company;
    }

    public function setId($id)
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
        $this->id = $this->id->getUnpackData($data, 0);
        $this->company = $this->company->getUnpackData($data, new Company, 1);
    }
}

// struct Members
// {
//     0 need list<string> names;
// }
// struct Company
// {
//     0 need int id;
//     1 need long code;
//     2 need string name;
//     16 optional string addr;
//     4 optional Members members;
//     5 optional list<Members> memberLists;
// }
// 
// struct User
// {
//     0 need int id;
//     1 optional Company company;
// }

$data = null;

$members = new Members;
$members->setNames(["aa", "cc", "dd"]);

$members2 = new Members;
$members2->setNames(["coco"]);

$company = new Company;
$company->setId(10);
$company->setCode(1111);
$company->setName('coco');
$company->setAddr('aaaaaaaaaa');
$company->setMembers($members);
$company->setMemberLists([$members, $members2]);

$user = new User;
$user->setId(1);
$user->setCompany($company);
$user->pack($data);

echo "压缩后总长度为".strlen($data)."\n";

$unpackData = coUnpack($data);

$obj = new User;
$obj->unpack($unpackData);
print_r($obj);


// $data = [
//     'id' => 1,
//     'company' => [
//         'id' => 10,
//         'code' => 1111,
//         'name' => 'coco',
//         'addr' => 'aaaaaaaaaa',
//         'members' => [
//             'names' => 'coco,fucongcong.jason'
//         ]
//     ]
// ];
// echo "json序列化长度为".strlen(json_encode($data))."\n";
