<?php
class AES {
    const DEFAULT_KEY = 'cb!@#';
	/**
	 * PHP DES 加密程式
	 *
	 * @param $key 密鑰（八個字元內）
	 * @param $encrypt 要加密的明文
	 * @return string 密文
	 */
	public static function encrypt ($encrypt, $key = self::DEFAULT_KEY)
	{
        // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 加入 Padding
	    $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_ECB);
	    $pad = $block - (strlen($encrypt) % $block);
	    $encrypt .= str_repeat(chr($pad), $pad);
	
	    // 不需要設定 IV 進行加密
	    $passcrypt = mcrypt_encrypt(MCRYPT_DES, $key, $encrypt, MCRYPT_MODE_ECB);
		return base64_encode($passcrypt);
	}
	
	/**
	 * PHP DES 解密程式
	 *
	 * @param $key 密鑰（八個字元內）
	 * @param $decrypt 要解密的密文
	 * @return string 明文
	 */
	public static function decrypt ($decrypt, $key = self::DEFAULT_KEY)
	{
        // 不需要設定 IV
	    $str = mcrypt_decrypt(MCRYPT_DES, $key, base64_decode($decrypt), MCRYPT_MODE_ECB);
	
	    // 根據 PKCS#7 RFC 5652 Cryptographic Message Syntax (CMS) 修正 Message 移除 Padding
	    $pad = ord($str[strlen($str) - 1]);
	    return substr($str, 0, strlen($str) - $pad);
	}
}