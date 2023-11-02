<?php

namespace App\Security;

use App\Entity\User;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Signer\Key\InMemory;

class AuthenticationToken
{
    public static function generateAuthenticationToken(User $userCredentials)
    {
        $encryptionType = new Sha256();
        $keyInBase64 = base64_encode($userCredentials->getEmail());
        $pemKey =  '-----BEGIN PRIVATE KEY-----' . $keyInBase64 . '-----END PRIVATE KEY-----';
        $base64Key = InMemory::base64Encoded($keyInBase64) ;
        $timestamp = time() + 3600;

        $token = (new Builder(new JoseEncoder(), ChainedFormatter::default()))
            ->issuedBy('alltechapp')
            ->permittedFor($userCredentials->getEmail())
            ->identifiedBy($_ENV['APP_SECRET'])
            ->issuedAt(new \DateTimeImmutable())
            ->canOnlyBeUsedAfter(new \DateTimeImmutable('@' . time()))
            ->expiresAt(new \DateTimeImmutable('@' . $timestamp))
            ->getToken($encryptionType, $base64Key);

        return $token;
    }
}