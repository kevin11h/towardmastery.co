<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Service\ArticleService;

class ArticleController extends Controller{
    /**
     * @Route("/api/article", name="get_articles", methods={"GET"})
     */
    public function getArticlesAction(
        Request $request,
        ArticleService $article_service
    ){
        $response = new Response();
        try{
            $articles = $article_service->search(array(
                'tag' => $request->query->get('tag')
            ));
            $array = array();
            foreach($articles as $article){
                array_push($array, $article->toArray());
            }
            $json = json_encode($array);
            return new Response($json, 200);
        } catch(\Exception $e){
            return new Response($e->getMessage(), 500);
        }
    }
}
