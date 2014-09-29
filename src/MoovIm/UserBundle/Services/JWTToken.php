<?php

// src/CRMBusinessApi/UserBundle/Services/JWTToken.php

namespace CRMBusinessApi\UserBundle\Services;

use JWT\Authentication\JWT;

class JWTToken
{
    private $userTockenSecret;
    private $container;

    /**
     * Construct JWT Class
     *
     * @param $serviceContainer
     * @param String $userTockenSecret
     */
    public function __construct($serviceContainer, $userTockenSecret)
    {
        $this->container = $serviceContainer;
        /**
         * Config User Tocken for JWT
         */
        $this->userTockenSecret = $userTockenSecret;
    }

    /**
     * JWT decode.
     *
     * @param String $token
     * @return JSON
     */
    public function decode($token)
    {
        $payloadObject = JWT::decode($token, $this->userTockenSecret);
        $payload = json_decode(json_encode($payloadObject), true);
        return $payload;
    }

    /**
     * JWT encode.
     *
     * @param Array $payload
     * @return String
     */
    public function encode($payload)
    {
        return JWT::encode($payload, $this->userTockenSecret);
    }

    /**
     * Create JWT token.
     *
     * @param \MoovIm\UserBundle\Entity\User $user
     * @param String $url
     * @return String
     */
    public function createToken($user, $url)
    {
        $payload = [
            'iss' => $url,
            'sub' => $user->getId(),
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];

        return $this->encode($payload);
    }

}