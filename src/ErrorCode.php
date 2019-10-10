<?php
namespace Waljqiang\MsgCrypt;
/**
 * error code 说明.
 * <ul>
 *    <li>-40001: 签名验证错误</li>
 *    <li>-40002: json解析失败</li>
 *    <li>-40003: sha加密生成签名失败</li>
 *    <li>-40004: encodingKey 非法</li>
 *    <li>-40005: clientID 校验错误</li>
 *    <li>-40006: aes 加密失败</li>
 *    <li>-40007: aes 解密失败</li>
 *    <li>-40008: 解密后得到的buffer非法</li>
 *    <li>-40009: base64加密失败</li>
 *    <li>-40010: base64解密失败</li>
 *    <li>-40011: 生成json失败</li>
 * </ul>
 */
class ErrorCode{
	public static $OK = 10000;
	public static $ValidateSignatureError = 600800100;
	public static $ParseXmlError = 600800101;
	public static $ComputeSignatureError = 600800102;
	public static $IllegalAesKey = 600800103;
	public static $ValidateclientIDError = 600800104;
	public static $EncryptAESError = 600800105;
	public static $DecryptAESError = 600800106;
	public static $IllegalBuffer = 600800107;
	public static $EncodeBase64Error = 600800108;
	public static $DecodeBase64Error = 600800109;
	public static $GenReturnXmlError = 600800110;
}