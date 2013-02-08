<?php
/**
 * @desc
 * This class provides encrypting and decrypting functionality using the mcrypt extension of php.
 *
 * @author     Christian Reinecke <christian.reinecke@web.de>
 * @version    1.0
 * @see        [url]http://php.net/mcrypt[/url]
 *
 * @example
 * <code>
 * define("MY_PRIVATE_KEY", "V^U^&Kbvw5ywcWHkjKkcw32");
 * $mcrypt = new Misc_Mcrypt(MY_PRIVATE_KEY);
 *
 * $plainCreditCardNo = "Did you know my credit card number? It's 1234-5678-9010-1112!";
 * printf("Plain: %s
", $plainCreditCardNo);
 *
 * $encryCreditCardNo = $mcrypt->encrypt($plainCreditCardNo);
 * printf("Encrypted: %s
", $encryCreditCardNo);
 *
 * $decryCreditCardNo = $mcrypt->decrypt($encryCreditCardNo);
 * printf("Decrypted: %s
", $decryCreditCardNo);
 *
 * printf("Restored: %u
", strcasecmp($plainCreditCardNo, $decryCreditCardNo) === 0);
 * printf("Data amount: +%d%%
", (strlen($encryCreditCardNo)/strlen($plainCreditCardNo)) * 100 - 100);
 * </code>
 */
class Anti_Mcrypt
{
    /**
     * @desc maximum key (or salt) length for the private key
     * @var int
     */
    const KeyMinLength = 10;
    const KeyMaxLength = 56; // mcrypt demand

    /**
     * @desc salt used to fill the private key to a total length of 56 characters (if necessary)
     * @var string
     * @warning
     * - NEVER CHANGE THIS VALUE ON A RUNNING SYSTEM,
     *   because it's part of the encryption key and if changed, old data can not be restored.
     * - Also make sure to store this key in another place, f.e. dotProject.
     * - Do not use special chars here, US-ANSI (ASCII 128) only (to avoid problems on charset conversions)
     */
    const SaltPad = '#89767bgxdrfpn4^N%KBRVJH$#%I ^N:LM(){NV CQX-][":.7n36F;-';

    /**
     * @desc some mcrypt required variable
     */
    private $_td;
    /**
     * @desc some mcrypt required variables
     * @warning
     * NEVER CHANGE THIS VALUES ON A RUNNING SYSTEM,
     * because the encryption key is based on these information snf if changed, old data can not be restored.
     */
    private $_cypher = MCRYPT_BLOWFISH;

    /**
     * @warning do not use ECB, it's not really secure, see links below
     * @see http://de.wikipedia.org/wiki/Cipher_Feedback_Mode
     * @see http://de.wikipedia.org/wiki/Electronic_Code_Book_Mode
     */
    private $_mode   = MCRYPT_MODE_CFB;

    /**
     * @desc private key for your application
     */
    private $_key = null;


    /**
     * constructor which expects the private key as first parameter
     * @throws Anti_Mcrypt_Exception
     * @params $key string private key
     */
    public function __construct($key)
    {
        if (!extension_loaded("mcrypt")) {
            throw new Anti_Mcrypt_Exception("mcrypt php extension not found but required");
        }
        $this->_td = mcrypt_module_open($this->_cypher, '', $this->_mode, '');
        $this->_setKey($key);
    }

    /**
     * @desc enrypt your plaintext with this method, the return value is the encrypted string
     * @throws Anti_Mcrypt_Exception
     * @param $plaintext string plaintext value
     * @return string encrypted, base64-encoded data (increases the data amount, but uses ASCII charachters)
     */
    public function encrypt($plaintext)
    {
        if (is_null($this->_key)) {
            throw new Anti_Mcrypt_Exception("no key set");
        }
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->_td), MCRYPT_RAND);
        mcrypt_generic_init($this->_td, $this->_key, $iv);
        $crypttext = mcrypt_generic($this->_td, $plaintext);
        mcrypt_generic_deinit($this->_td);
        return base64_encode($iv . $crypttext);
    }

    /**
     * @desc decrypt your encrypted data with this method, the return value is the plaintext string
     * @throws Anti_Mcrypt_Exception
     * @param $crypttext string encrypted value
     * @return string plaintext
     */
    public function decrypt($crypttext)
    {
        if (is_null($this->_key)) {
            throw new Anti_Mcrypt_Exception("no key set");
        }
        $crypttext = base64_decode($crypttext);
        $ivsize = mcrypt_get_iv_size($this->_cypher, $this->_mode);
        $iv = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        mcrypt_generic_init($this->_td, $this->_key, $iv);
        $plaintext = mdecrypt_generic($this->_td, $crypttext);
        mcrypt_generic_deinit($this->_td);
        return $plaintext;
    }

    /**
     * @desc close mcrypt module, do not call manually
     */
    public function __destruct()
    {
        mcrypt_module_close($this->_td);
    }

    /**
     * @param $key string private key
     */
    private function _setKey($key)
    {
        if (strlen($key) < self::KeyMinLength) {
            throw new Anti_Mcrypt_Exception("the key is too short, expected at minimum of %s, given %s", 0, array(self::KeyMinLength, strlen($key)));
        } else if (strlen($key) > self::KeyMaxLength) {
            throw new Anti_Mcrypt_Exception("the key is too long, expected a maximum of %s, given %s", 0, array(self::KeyMaxLength, strlen($key)));
        }
        $this->_key = substr($key . self::SaltPad, 0, self::KeyMaxLength);
    }
}