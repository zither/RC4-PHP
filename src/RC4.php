<?php

class RC4 
{
    const ENCRYPT_MODE_UPDATE = 1;
    const ENCRYPT_MODE_NORMAL = 0;

    protected $password = null;
    protected $encryptMode = false;
    protected $sBox = array();
    protected $si = 0;
    protected $sj = 0;

    public function __construct($password, $mode = 0)
    {
        $this->password = $password;
        $this->encryptMode = $mode;
        $this->initCipher();
    }

    protected function  initCipher() 
    {
        $passwordLength = strlen($this->password);
        $key = array();
        for ($i = 0; $i < 256; $i++) {
            $key[$i] = ord($this->password[$i % $passwordLength]);
            $this->sBox[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i ++) {
            $j = ($j + $this->sBox[$i] + $key[$i]) % 256;
            list($this->sBox[$i], $this->sBox[$j]) = array($this->sBox[$j], $this->sBox[$i]);
        }
        $this->si = $this->sj = 0;
    }

    public function encrypt($plaintext) 
    {
        $plaintextLength = strlen($plaintext);
        $ciphertext = '';
        for ($n = 0; $n < $plaintextLength; $n++) {
            $this->si = ($this->si + 1) % 256;
            $this->sj = ($this->sj + $this->sBox[$this->si]) % 256;
            list($this->sBox[$this->si], $this->sBox[$this->sj]) = array(
                $this->sBox[$this->sj], 
                $this->sBox[$this->sj]
            );
            $k = $this->sBox[($this->sBox[$this->si] + $this->sBox[$this->sj]) % 256];
            $ciphertext .= chr(ord($plaintext[$n]) ^ $k);
        }
        if ($this->encryptMode === static::ENCRYPT_MODE_NORMAL) {
            $this->resetCipher();
        }
        return $ciphertext;
    }

    protected function resetCipher()
    {
        $this->initCipher();
    }

    public function decrypt($plaintext)
    {
        return $this->encrypt($plaintext);
    }
}
