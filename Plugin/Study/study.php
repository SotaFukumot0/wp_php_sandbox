<?php
namespace Study;
//
echo "Hello from PHP\n";
//
$variable = "variable\n";
echo $variable;
//
define("CONSTANT_VALUE","ConstVal\n");
echo CONSTANT_VALUE;
//
$variable .= CONSTANT_VALUE;
echo $variable;
//
$arrayNonKey = array(100,200,300);
$arrayKey = array(1=>400,2=>500,3=>600);
print_r($arrayKey);
var_dump($arrayKey);
$arrayNonKey[] = 101;
$arrayNonKey[] = 201;
$arrayNonKey[] = 301;
$arrayNonKey[] = 401;
$arrayNonKey[0] = 102;
print_r($arrayNonKey,false);
$arrayKey[] = 399;
print_r($arrayKey);
//
for ($i = 0; $i <= 3; $i++) {
    echo "for means $i.\n";
}
//
function functest(&$str){
    $str = "bar\n";
}
$foobar = "foo\n";
echo $foobar;
functest($foobar);
echo $foobar;
//
class SampleA {
    function __construct() {
        print "SampleA constructor\n";
    }
    public $aa="aa\n";
    protected $bb="bb\n";
    private $cc="cc\n";
    public function getPrivVal(){
        echo $this->cc;
    }
}
class SampleB extends SampleA {
    const DD = "DD\n";
    public function getParentVal(){
        return $this->aa;
    }
    public function getParentValFunc(){
        $this->getPrivVal();
    }
}
$sampleAB = new SampleB;
echo $sampleAB->aa;
echo $sampleAB->getParentVal();
echo $sampleAB->getParentValFunc();
echo $sampleAB::DD;
echo __NAMESPACE__ . "\n";