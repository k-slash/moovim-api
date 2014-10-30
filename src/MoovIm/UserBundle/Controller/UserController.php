<?php

namespace MoovIm\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class UserController extends FOSRestController
{
    /**
     * @var EntityManager
     */
    protected $em;
    protected $profile;

    /**
     * Connect user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function connectAction(Request $request)
    {
        $oauthGoogle = $this->get('oauth_google');
        //Get user token
        $json = $oauthGoogle->connect();
        return $json;
    }

    /**
     * Get user connected.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserAuthenticatedAction(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();
        $authorization = $request->headers->get('Authorization');
        if ($authorization) {
            $jwtToken = $this->get('jwt_token');
            $token = explode(' ', $authorization)[1];
            $payload = $jwtToken->decode($token);
            $user  = $this->em->getRepository('MoovImUserBundle:User')->find($payload['sub']);
            if (!$user) {
                return new JsonResponse([
                    'message' => 'API Access denied'
                ],403);
            } else {
                //Try to get user information and response status
                return new JsonResponse([
                    'user_id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'first_name' => $user->getFirstName(),
                    'family_name' => $user->getFamilyName(),
                    'picture' => $user->getPicture(),
                ]);
            }
        } else {
            return new JsonResponse([
                'message' => 'Access denied'
            ],403);
        }

    }


}
