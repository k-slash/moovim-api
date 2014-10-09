<?php

namespace MoovIm\MovieBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use \Tmdb\Helper\ImageHelper;

class MovieController extends FOSRestController
{
    /**
     * Get movies coming soon
     * @param Request $request
     * @return mixed
     */
    public function getMoviesComingSoonAction(Request $request)
    {
        $client = $this->get('wtfz_tmdb.client');
        $json = $client->getMoviesApi()->getNowPlaying();
        return new JsonResponse($json);
    }

    public function detailAction(Request $request){
        $repository = $this->get('wtfz_tmdb.movie_repository');
        $movie = $repository->load(87421);
        var_dump($movie);
        exit();
        //$json = $client->getSearchApi()->searchMovies('Titanic');
        //var_dump($json);
    }
}
