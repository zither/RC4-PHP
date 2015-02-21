<?php

require __DIR__ . "/src/RC4.php";

$password = md5("password", true);
$plaintext = "0123456789abcdefghijklmnopqrstuvwxyz";

$encryptor = new RC4($password);
$ciphertext = $encryptor->encrypt($plaintext);
printf(
    "------ NORMAL MODE ------\nplaintext: %s\n", 
    $encryptor->decrypt($ciphertext)
);

$decryptor = new RC4($password, RC4::ENCRYPT_MODE_UPDATE);
$subCiphertext1 = substr($ciphertext, 0, 16);
$subCiphertext2 = substr($ciphertext, 16);
printf(
    "------ UPDATE MODE ------\nsubPlaintext1: %s\nsubPlaintext2: %s\n", 
    $decryptor->decrypt($subCiphertext1),
    $decryptor->decrypt($subCiphertext2)
);
