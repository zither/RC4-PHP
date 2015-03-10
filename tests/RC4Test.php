<?php 

require dirname(__DIR__) . "/src/RC4.php";

class RC4Test extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstruct()
    {
        $password = "password";
        $encryptor = new RC4($password);
        $this->assertAttributeEquals($password, "password", $encryptor);
        $this->assertAttributeEquals(RC4::ENCRYPT_MODE_NORMAL, "encryptMode", $encryptor);

        $encryptor = new RC4($password, RC4::ENCRYPT_MODE_UPDATE);
        $this->assertAttributeEquals(RC4::ENCRYPT_MODE_UPDATE, "encryptMode", $encryptor);

        $encryptor = new RC4($password, 0x03);
    }

    public function testInitAndResetCipher()
    {
        $sBox = array(
            76,210,71,79,134,51,36,8,184,37,238,185,239,107,61,114,
            222,80,213,215,230,106,242,13,96,45,252,138,142,221,70,187,
            34,205,226,248,117,113,211,39,208,78,14,64,44,57,0,2,
            86,196,33,97,4,194,32,172,147,195,90,3,72,188,151,206,
            234,135,140,247,228,102,93,128,136,5,163,21,249,92,81,189,
            59,47,91,35,46,104,150,42,236,214,63,162,30,95,224,121,
            9,89,131,25,180,253,207,154,139,10,27,109,19,250,66,190,
            99,12,212,122,245,158,53,129,84,120,55,243,68,1,146,123,
            149,157,153,54,219,174,77,144,28,220,62,125,75,29,199,127,
            18,22,169,170,179,132,31,200,38,67,209,223,165,116,155,16,
            156,52,203,101,227,83,241,26,160,118,175,50,20,119,182,181,
            87,197,73,133,237,186,126,201,40,108,6,233,183,60,198,48,
            11,141,145,69,166,216,105,193,65,231,178,192,229,225,218,232,
            49,43,15,161,94,254,177,82,23,110,56,251,240,168,176,58,
            152,255,98,191,171,41,115,235,111,159,244,24,148,202,164,103,
            74,143,85,204,137,88,17,130,217,167,124,246,112,173,7,100
        );
        $encryptor = new RC4("password");
        $this->assertAttributeEquals($sBox, "sBox", $encryptor);
        $this->assertAttributeEquals(0, "si", $encryptor);
        $this->assertAttributeEquals(0, "sj", $encryptor);

        $encryptor->encrypt("plaintext");
        $this->assertAttributeEquals($sBox, "sBox", $encryptor);
        $this->assertAttributeEquals(0, "si", $encryptor);
        $this->assertAttributeEquals(0, "sj", $encryptor);
    }

    /**
     * @depends testConstruct
     */
    public function testNormalEncryptAndDecrypt()
    {
        $password = "password";
        $plaintext = "A simple RC4 cipher implementation in php.";
        $encryptor = new RC4($password);
        $ciphertext = $encryptor->encrypt($plaintext);
        $decryptor = new RC4($password);
        $this->assertEquals($plaintext, $decryptor->decrypt($ciphertext));
    }

    /**
     * @depends testConstruct
     */
    public function testUpdateEncryptAndDecrypt()
    {
        $password = "password";
        $plaintext = "A simple RC4 cipher implementation in php.";
        $encryptor = new RC4($password, RC4::ENCRYPT_MODE_UPDATE);
        $ciphertext = $encryptor->encrypt($plaintext);
        $subCiphertext1 = substr($ciphertext, 0, 8);
        $subCiphertext2 = substr($ciphertext, 8);

        $decryptor = new RC4($password, RC4::ENCRYPT_MODE_UPDATE);
        $subPlaintext1 = $decryptor->decrypt($subCiphertext1);
        $subPlaintext2 = $decryptor->decrypt($subCiphertext2);
        $this->assertEquals($plaintext, $subPlaintext1 . $subPlaintext2);        
    }
}
