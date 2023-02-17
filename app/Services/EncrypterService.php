<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

class EncrypterService
{
    /**
     * Encrypts data using OpenSSL and the AES-256-CBC cipher.
     *
     * @throws \Illuminate\Contracts\Encryption\EncryptException
     */
    public function encrypt(string $data): string
    {
        $iv = random_bytes(16);
        $key = env('APP_KEY');
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);

        if ($encrypted === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        // http://urlhub.test/delete/7iWg1K3gpbXWyrTUy3B7CA==
        return base64_encode($iv.$encrypted);
        // http://urlhub.test/delete/9kRsfILFw9NeOECXcJMqwQ==
        // return $encrypted;
    }

    /**
     * Decrypts data using OpenSSL and the AES-256-CBC cipher.
     *
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     */
    public function decrypt(string $data): string
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $key = env('APP_KEY');
        $encrypted = substr($data, 16);
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);

        if ($decrypted === false) {
            throw new DecryptException('Could not decrypt the data.');
        }

        return $decrypted;
    }
}
