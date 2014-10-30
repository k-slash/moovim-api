<?php

// src/CRMBusinessApi/UserBundle/Services/ApiUser.php

namespace MoovIm\UserBundle\Services;

use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use MoovIm\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use MoovIm\UserBundle\Services\JWTToken;
use GuzzleHttp\Exception\BadResponseException;

class OAuthGoogle
{

    /**
     * @var EntityManager
     */
    private $em;

    private $oauthAccessTokenUrl;

    private $oauthPeopleApiUrl;

    private $googleClientId;

    private $googleClientSecret;

    private $client;

    private $url;

    private $content;

    private $request;

    private $jwtToken;

    /**
     * Construct OAuthGoogle
     *
     * @param RequestStack $requestStack
     * @param EntityManager $entityManager
     * @param JWTToken $jwtToken
     * @param String $oauthAccessTokenUrl
     * @param String $oauthPeopleApiUrl
     * @param String $googleClientId
     * @param String $googleClientSecret
     */
    public function __construct(RequestStack $requestStack, EntityManager $entityManager, JWTToken $jwtToken, $oauthAccessTokenUrl, $oauthPeopleApiUrl, $googleClientId, $googleClientSecret)
    {
        $this->em = $entityManager;
        $this->request = $requestStack->getCurrentRequest();

        /**
         * Get url for google oauth
         */
        $this->oauthAccessTokenUrl = $oauthAccessTokenUrl;
        $this->oauthPeopleApiUrl = $oauthPeopleApiUrl;

        /**
         * Generate googleClientId and googleClientSecret for google oauth
         */
        $this->googleClientId = $googleClientId;
        $this->googleClientSecret = $googleClientSecret;

        /**
         * Generate Guzzle Client
         */
        $this->client = new Client();

        /**
         * Generate the content of the request
         */
        $this->content = $this->request->getContent();
        $this->url = $this->request->getBaseUrl();

        $this->jwtToken = $jwtToken;
    }

    /**
     * Connect user and get google user data
     * @return json
     */
    public function connect()
    {
        $obj = json_decode($this->content);
        $oauthClientId = $obj->{'clientId'};
        $oauthCode = $obj->{'code'};
        $oauthRedirectUri = $obj->{'redirectUri'};

        //Check site access
        if ($oauthClientId === $this->googleClientId) {
            $params = [
                'code' => $oauthCode,
                'client_id' => $oauthClientId,
                'redirect_uri' => $oauthRedirectUri,
                'grant_type' => 'authorization_code',
                'client_secret' => $this->googleClientSecret,
            ];

            // Step 1. Exchange authorization code for access token.
            $accessTokenResponse = $this->client->post($this->oauthAccessTokenUrl, ['body' => $params]);
            $accessToken = $accessTokenResponse->json()['access_token'];
            $headerAuthorization = ['Authorization' => 'Bearer ' . $accessToken];

            // Step 2. Retrieve profile information about the current user.
            $profileResponse = $this->client->get($this->oauthPeopleApiUrl, [
                'headers' => $headerAuthorization
            ]);

            // Step 3. Create or update user
            $profile = $profileResponse->json();

            $user  = $this->em->getRepository('MoovImUserBundle:User')->findOneByEmail($profile['email']);
            if (!$user) {
                $user = new User();
                $user->setEmail($profile['email']);

            }
            $user->setPicture($profile['picture']);
            $user->setFirstName($profile['given_name']);
            $user->setFamilyName($profile['family_name']);
            $token = $this->jwtToken->createToken($user, $this->url);
            $user->setToken($token);
            $this->em->persist($user);
            $this->em->flush();
            return new JsonResponse([
                'token' => $token
            ]);

        } else {
            return new JsonResponse([
                'message' => 'Access denied'
            ],401);
        }

    }
}