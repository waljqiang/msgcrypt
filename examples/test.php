<?php
require __DIR__ . "/../vendor/autoload.php";
use Waljqiang\MsgCrypt\MsgCrypt;

$encodingKey = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG";
$token = "waljqiang";
$timeStamp = "1409304348";
$nonce = "123456";
$clientID = "ycywqrexgprjkdovkpznblamywql";
//加密
$pc = new MsgCrypt($token,$encodingKey,$clientID);
$msg = json_encode(['a' => 1,'b' =>2]);
$rs = $pc->encryptMsg($msg,$nonce,$timeStamp);
if($rs['code'] == 10000){
	$encryptMsg = $rs['data'];
	var_dump("加密后消息：" . $encryptMsg);
}else{
	var_dump($rs);
}


//解密
$encryptMsg = '{"encrypt":"6AEwD85pllCaImKG62VA8EEZ1v8JQZSfUrQlvtbXhmisMT67v72oLH7n6r+mEHtboO311FIQaCHjUP\/q\/AxXlw==","signature":"371d61716ccf764195624b1fbc18431b12acb0ba","timestamp":"1409304348","nonce":"123456"}';
$signature = '371d61716ccf764195624b1fbc18431b12acb0ba';
$timeStamp = '1409304348';
$nonce = '123456';
$rs = $pc->decryptMsg($signature,$timeStamp,$nonce,$encryptMsg);
var_dump($rs);