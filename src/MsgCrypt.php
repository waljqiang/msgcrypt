<?php
namespace Waljqiang\MsgCrypt;

class MsgCrypt{
	private $token;
	private $encodingKey;
	private $clientID;

	/**
	 * 构造函数
	 * @param $token string 鉴权字符串token
	 * @param $encodingKey string 加密key
	 * @param $clientID string 客户端id
	 */
	public function __construct($token, $encodingKey, $clientID)
	{
		$this->token = $token;
		$this->encodingKey = $encodingKey;
		$this->clientID = $clientID;
	}

	/**
	 * 将消息加密打包.
	 * <ol>
	 *    <li>对要发送的消息进行AES-CBC加密</li>
	 *    <li>生成安全签名</li>
	 *    <li>将消息密文和安全签名打包成json格式</li>
	 * </ol>
	 *
	 * @param $msg string 待加密的消息，json格式的字符串
	 * @param $timeStamp string 时间戳，可以自己生成，也可以用URL参数的timestamp
	 * @param $nonce string 随机串，可以自己生成，也可以用URL参数的nonce
	 *
	 */
	public function encryptMsg($msg, $nonce,$timeStamp=null){
		$pc = new Prpcrypt($this->encodingKey);

		//加密
		$array = $pc->encrypt($msg, $this->clientID);
		$ret = $array[0];
		if ($ret != ErrorCode::$OK) {
			return json_encode(['code' => $ret,'msg' => 'Encode Error']);
		}

		if ($timeStamp == null) {
			$timeStamp = time();
		}
		$encrypt = $array[1];

		//生成安全签名
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->token, $timeStamp, $nonce, $encrypt);
		$ret = $array[0];
		if ($ret != ErrorCode::$OK) {
			return json_encode(['code' => $ret,'msg' => 'Generate signature error']);
		}
		$signature = $array[1];

		//生成发送的json
		$jsonParse = new JsonParse;
		$encryptMsg = $jsonParse->generate($encrypt, $signature, $timeStamp, $nonce);
		return ['code' => ErrorCode::$OK,'data' => $encryptMsg];
	}


	/**
	 * 检验消息的真实性，并且获取解密后的明文.
	 * <ol>
	 *    <li>利用收到的密文生成安全签名，进行签名验证</li>
	 *    <li>若验证通过，则提取json中的加密消息</li>
	 *    <li>对消息进行解密</li>
	 * </ol>
	 *
	 * @param $msgSignature string 签名串，对应URL参数的signature
	 * @param $timestamp string 时间戳 对应URL参数的timestamp
	 * @param $nonce string 随机串，对应URL参数的nonce
	 * @param $postData string 密文，对应POST请求的数据
	 *
	 */
	public function decryptMsg($msgSignature, $timestamp = null, $nonce, $postData){
		if (strlen($this->encodingKey) != 43) {
			return json_encode(['code' => ErrorCode::$IllegalAesKey,'msg' => 'encodingKey invalid']);
		}

		$pc = new Prpcrypt($this->encodingKey);

		//提取密文
		$jsonParse = new JsonParse;
		$msg = $jsonParse->extract($postData);

		if ($timestamp == null) {
			$timestamp = time();
		}

		$encrypt = $msg->encrypt;

		//验证安全签名
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->token, $timestamp, $nonce, $encrypt);
		$ret = $array[0];

		if ($ret != ErrorCode::$OK) {
			return json_encode(['code' => $ret,'msg' => 'Generate signature error']);
		}

		$signature = $array[1];
		if ($signature != $msgSignature) {
			return json_encode(['code' => ErrorCode::$ValidateSignatureError,'msg' => 'The signagure invalid']);
		}

		$result = $pc->decrypt($encrypt, $this->clientID);
		if ($result[0] != ErrorCode::$OK){
			return json_encode(['code' => $result[0],'msg' => 'Decrypt failure']);
		}
		return ['code' => ErrorCode::$OK,'data' => $result[1]];
	}
}