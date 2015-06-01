<?php

namespace BlockCypher\AppCommon\App\Service\Internal;

use BlockCypher\AppCommon\App\Service\Decryptor;
use BlockCypher\AppCommon\App\Service\Encryptor;

/**
 * Class for AES256 encryption
 *
 * @author Victor Melnik <melnikvictorl@gmail.com>
 */
class AES256Encryptor implements Encryptor, Decryptor
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $initializationVector;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->secretKey = md5($key);
        $this->initializationVector = mcrypt_create_iv(
            mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
            MCRYPT_RAND
        );
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($data)
    {
        return trim(base64_encode(mcrypt_encrypt(
            MCRYPT_RIJNDAEL_256,
            $this->secretKey,
            $data,
            MCRYPT_MODE_ECB,
            $this->initializationVector
        )));
    }

    /**
     * {@inheritdoc}
     */
    function decrypt($data)
    {
        return trim(mcrypt_decrypt(
            MCRYPT_RIJNDAEL_256,
            $this->secretKey,
            base64_decode($data),
            MCRYPT_MODE_ECB,
            $this->initializationVector
        ));
    }
}
