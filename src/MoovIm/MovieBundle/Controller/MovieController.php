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

    public function detailAction($id){
        $client = $this->get('wtfz_tmdb.client');
        $movie = $client->getMoviesApi()->getMovie($id);
        $movie += $client->getMoviesApi()->getImages($id);
        $movie += $client->getMoviesApi()->getVideos($id);
        /*$a1 = json_decode( $movie, true );
        $a2 = json_decode( $images, true );

        $res = array_merge_recursive( $a1, $a2 );

        $resJson = json_encode( $res );*/
        //var_dump($movie);
        //exit();
        return new JsonResponse($movie);
        //$json = $client->getSearchApi()->searchMovies('Titanic');
        //var_dump($json);
    }
}
