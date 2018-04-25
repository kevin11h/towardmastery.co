<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\ArticleService;
use App\Service\TagService;
use App\Entity\Article;
use App\Entity\ArticleTranslation;

class FrontController extends Controller{
    /**
     * @Route("/{_locale}", name="landing_page", requirements = {"_locale": "en|fr"})
     */
    public function landingPage(
        $_locale = 'en',
        ArticleService $article_service,
        TagService $tag_service
    ){
        // return new Response("test");
        $articles = $article_service->readAll();
        $tags = $tag_service->readAll();
        return $this->render('index.html.twig', array(
            "articles" => $articles,
            'tags' => $tags
        ));
    }
    /**
     * @Route("/{_locale}/article/{id}", name="article_view_page")
     */
    public function articleAction(Article $article){
        return $this->render('view_article_page.html.twig', array(
            "article" => $article
        ));
    }
}
?>
