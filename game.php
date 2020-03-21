<?php
ini_set('log_errors','on');
ini_set('error_log','php.log');
//デバッグフラグ
$debug_flg = true;
//デバッグ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
       error_log('デバッグ:'.$str);
    }
}
session_start();

//モンスター達を格納
$monsters = array();

class Sex{
    const MAN = 1;
    const WOMAN = 2;
}
//抽象クラス
abstract class Creature{
    protected $name;
    protected $hp;
    protected $attackMin;
    protected $attackMax;
    abstract public function sayCry();
    public function setName($str){
        $this->name = $str;
    }
    public function getName(){
        return $this->name;
    }
    public function setHp($num){
        $this->hp = $num;
    }
    public function getHp(){
        return $this->hp;
    }
    public function attack($targetObj){
        History::set($this->getName().'の攻撃!!');
        $attackPoint = mt_rand($this->attackMin,$this->attackMax);
        if(!mt_rand(0,9)){
            History::set('クリティカルヒット!!');
            $attackPoint = $attackPoint * 1.5;
            $attackPoint = (int)$attackPoint;
        }
        $targetObj->setHp($targetObj->getHp() - $attackPoint);
        History::set($attackPoint.'のダメージ!!');
    }
}
//人クラス
class Human extends Creature{
    protected $sex;
    public function __construct($name,$sex,$hp,$attackMin,$attackMax){
        $this->name = $name;
        $this->sex = $sex;
        $this->hp = $hp;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
    }
    public function setSex($num){
        $this->sex = $num;
    }
    public function getSex(){
        return $this->sex;
    }
    public function sayCry(){
        History::set($this->getName().'が叫ぶ!!');
        switch($this->sex){
            case Sex::MAN :
                History::set('ぐはぁっ!!');
                break;
            case Sex::WOMAN :
                History::set('きゃぁっ!!');
                break;
        }
    }
}
//モンスタークラス
class Monster extends Creature{
    protected $img;

    public function __construct($name,$hp,$img,$attackMin,$attackMax){
        $this->name = $name;
        $this->hp = $hp;
        $this->img = $img;
        $this->attackMin = $attackMin;
        $this->attackMax = $attackMax;
    }
    public function getImg(){
        return $this->img;
    }
    public function sayCry(){
        History::set($this->getName().'が叫ぶ!!');
        History::set('「ぐおっ!」');
    }
}
class MagicMonster extends Monster{
    private $magicAttack;
    function __construct($name,$hp,$img,$attackMin,$attackMax,$magicAttack){
        parent::__construct($name,$hp,$img,$attackMin,$attackMax);
        $this->magicAttack = $magicAttack;
    }
    public function attack($targetObj){
        if(!mt_rand(0,4)){
            History::set($this->getName().'の宝具!!');
            $targetObj->setHp($this->getHp() - $magicAttack);
            History::set($this->magicAttack.'ポイントのダメージを受けた!!');
        }else{
            parent::attack($targetObj);
        }
    }
}
interface HistoryInterFace{
    public static function set($str);
    public static function clear();
}
class History implements HistoryInterFace{
    public static function set($str){
        if(empty($_SESSION['history'])) $_SESSION['history'] = '';
        $_SESSION['history'] .= $str.'<br>';
    }
    public static function clear(){
        unset($_SESSION['history']);
    }
}
//インスタンス生成
$human = new Human('おれ',Sex::MAN,500,40,120);
$monsters[] = new MagicMonster('アサシン',200,'img/assassin.gif', 20, 30, mt_rand(20,50));
$monsters[] = new MagicMonster('バーサーカー',200,'img/barserker.gif', 20, 60, mt_rand(10,100));
$monsters[] = new MagicMonster('キャスター',200,'img/caster.gif', 20, 50, mt_rand(60,70));
$monsters[] = new MagicMonster('ギルガメッシュ',200,'img/girugame.gif', 20, 50, mt_rand(60,120));
$monsters[] = new MagicMonster('ライダー',200,'img/rider.gif', 20, 50, mt_rand(60,110));

function createMonster() {
    global $monsters;
    $monster = $monsters[mt_rand(0,4)];
    History::set($monster->getName().'が現れた！！');
    $_SESSION['monster'] = $monster;
}
function createHuman(){
    global $human;
    $_SESSION['human'] = $human;
}
function init(){
    History::clear();
    History::set('英霊を倒せ！！');
    $_SESSION['knockDownCount'] = 0;
    createHuman();
    createMonster();
}
function gameOver() {
    $_SESSION = array();
}


function showImg($path) {
    if(empty($_SESSION)) {
        return 'img/fate_title.png';
    }elseif($_SESSION['result']){
        return 'img/fate-result.jpg';
    }else{
        return $path;
    }
}

if(!empty($_POST)) {
    $attackFlg = (!empty($_POST['attack'])) ? true : false;
    $startFlg = (!empty($_POST['start'])) ? true : false;
    $escapeFlg = (!empty($_POST['escape'])) ? true : false;
    $endFlg = (!empty($_POST['end'])) ? true : false;
    $resultFlg = (!empty($_SESSION['result']))? true : false;
    error_log('POSTされたぞ');


    if($startFlg) { // 分岐。スタートなのか攻撃なのか
        init(); 
    }else{
        
        //攻撃を押した場合
        if($attackFlg) {

            $_SESSION['human']->attack($_SESSION['monster']);
            $_SESSION['monster']->sayCry();

            //敵の攻撃
            $_SESSION['monster']->attack($_SESSION['human']);
            $_SESSION['human']->sayCry();
            
            $attackFlg= false;
            //ゲームオーバー条件
            if($_SESSION['human']->getHp() <= 0) {
                $startFlg = false;
                $_SESSION['result'] = true; 
            }else {
                //次のモンスター出現処理
                if($_SESSION['monster']->getHp() <= 0) {
                    History::set($_SESSION['monster']->getName().'を倒した！！');
                    History::set('令呪を1つ獲得した！！');
                    createMonster();
                    $_SESSION['knockDownCount'] = $_SESSION['knockDownCount'] + 1;
                }
            }
        }elseif($escapeFlg){//逃げたら
            History::set('逃亡した！');
            $escapeFlg = false;
            createMonster();
        }elseif($endFlg){
            $endFlg= false;
            gameOver();
        }
    }
    $_POST = array();   
}

?>