<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\ArticleService;
use App\Entity\Article;
use App\Entity\ArticleTranslation;

class FrontController extends Controller{
    /**
     * @Route("/{_locale}", name="landing_page", requirements = {"_locale": "en|fr"})
     */
    public function landingPage(
        $_locale = 'en',
        ArticleService $article_service
    ){
        $articles = $article_service->readAll();
        return $this->render('landing.html.twig', array(
            "articles" => $articles
        ));
    }
    /**
     * @Route("/{_locale}/article/{slug}", name="article_view_page")
     */
    public function articleAction(ArticleTranslation $article){
        return $this->render('view_article_page.html.twig', array(
            "article" => $article->getArticle()
        ));
    }
}
?>
