<?php 

$sort = new RSort;
$sort->doSort();

class RSort
{   
    protected $test;

    public function __construct()
    {
        $this->test = new NFile();
    }

    function doSort()
    {
        $d1 = $this->test->getData(1);
        $d2 = $this->test->getData(2);

        $this->compare($d1, $d2);
    }

    function compare($d1, $d2)
    {
        if ($d1 === false && $d2 === false) {
            echo "finish";
            return;
        }

        if ($d1 === false || ($d2 !== false && $d1['v'] > $d2['v'])) {
            file_put_contents("res.txt", $d2['v']."\n", FILE_APPEND);
            if ($d2['k'] == 1) {
               $d2 = $this->test->getData(1); 
            }
            if ($d2['k'] == 2) {
               $d2 = $this->test->getData(2); 
            }
            //继续对比
            $this->compare($d1, $d2);
        } else {
            file_put_contents("res.txt", $d1['v']."\n", FILE_APPEND);
            if ($d1['k'] == 1) {
               $d1 = $this->test->getData(1); 
            }
            if ($d1['k'] == 2) {
               $d1 = $this->test->getData(2); 
            }
            //继续对比
            $this->compare($d1, $d2);
        }
    }
}

class NFile
{
    protected $array1 = [1, 3, 3, 6, 9, 15];
    protected $array2 = [1, 1, 2, 5, 7, 9, 12];
    protected $p1 = 0;
    protected $p2 = 0;

    function getData($tag)
    {   
        if ($tag == 1) {
            if (isset($this->array1[$this->p1])) {
                $r = ['v' => $this->array1[$this->p1], 'k' => $tag];
                $this->p1++;
                return $r;
            }
        } else {
            if (isset($this->array2[$this->p2])) {
                $r = ['v' => $this->array2[$this->p2], 'k' => $tag];
                $this->p2++;
                return $r;
            }
        }

        return false;
    }
}



