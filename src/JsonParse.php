<?php
namespace Waljqiang\MsgCrypt;
class JsonParse{
	/**
	 * 生成json消息
	 * @param string $encrypt 加密后的消息密文
	 * @param string $signature 安全签名
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 */
	public function generate($encrypt, $signature, $timestamp, $nonce){
		$data = [
			'encrypt' => $encrypt,
			'signature' => $signature,
			'timestamp' => $timestamp,
			'nonce' => $nonce
		];
		return json_encode($data);
	}

	public function extract($text){
		return json_decode($text);
	}
}