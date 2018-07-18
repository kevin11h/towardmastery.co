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
            // filtering
            $categories = $request->query->get('category_filter');
            $filters = array();
            if(!empty($categories)){
              foreach($categories as $category => $boolean){
                if($boolean){
                  $filter = array(
                    "criteria" => 'category',
                    "filter" => $category
                  );
                  array_push($filters, $filter);
                }
              }
            }

            // ordering
            $direction = $request->query->get('orderby');
            $orderby = array();
            if(!empty($direction)){
              $orderby = array(
                'criteria' => 'date',
                'direction' => $direction
              );
            }

            // paging
            $elem_per_page = 10;
            // if(!empty($request->query->get('per_page'))){
            //     $elem_per_page = $request->query->get('per_page');
            // }
            $page_nb = $request->query->get('current_page');
            // if(!empty($request->query->get('page_nb'))){
            //     $page_nb = $request->query->get('page_nb');
            // }
            $paging = array('current_page'=>$page_nb, 'result_per_page'=>$elem_per_page);

            // query
            $articles = $article_service->search($filters, $orderby, $paging);
            if(!empty($articles)){
                $array = array();
                foreach($articles as $article){
                  array_push($array, $article->toArray());
                }
                $json = json_encode($array);
                return new Response($json);
            } else {
                return new Response('EOF');
            }
        } catch(\Exception $e){
            return $response->setStatusCode('404');
        }
    }
}
