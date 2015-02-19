<?php

class RC4 
{
    const ENCRYPT_MODE_UPDATE = 1;
    const ENCRYPT_MODE_NORMAL = 0;

    protected $password = null;
    protected $mode = false;
    protected $S = array();
    protected $i = 0;
    protected $j = 0;

    public function __construct($password, $mode = 0)
    {
        $this->password = $password;
        $this->mode = $mode;
        $this->initCipher();
    }

    protected function  initCipher() 
    {
        $passwordLength = strlen($this->password);
        $key = array();
        for ($i = 0; $i < 256; $i++) {
            $key[$i] = ord($this->password[$i % $passwordLength]);
            $this->S[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i ++) {
            $j = ($j + $this->S[$i] + $key[$i]) % 256;
            list($this->S[$i], $this->S[$j]) = array($this->S[$j], $this->S[$i]);
        }
        $this->i = $this->j = 0;
    }

    public function encrypt($plaintext) 
    {
        $plaintextLength = strlen($plaintext);
        $ciphertext = '';
        if ($this->mode === static::ENCRYPT_MODE_NORMAL) {
            $this->initCipher();
        }
        for ($n = 0; $n < $plaintextLength; $n++) {
            $this->i = ($this->i + 1) % 256;
            $this->j = ($this->j + $this->S[$this->i]) % 256;
            list($this->S[$this->i], $this->S[$this->j]) = array(
                $this->S[$this->j], 
                $this->S[$this->i]
            );
            $K = $this->S[($this->S[$this->i] + $this->S[$this->j]) % 256];
            $ciphertext .= chr(ord($plaintext[$n]) ^ $K);
        }
        return $ciphertext;
    }

    public function decrypt($plaintext)
    {
        return $this->encrypt($plaintext);
    }
}
