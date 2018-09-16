<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Service\ArticleService;
use App\Service\TagService;

use App\Entity\Article;
use App\Entity\ArticleTranslation;

class FrontController extends Controller{
    /**
     * @Route("/", name="landing_page", methods={"GET"})
     */
    public function landingPage(
        ArticleService $article_service,
        TagService $tag_service,
        Request $request
    ){
        $article_count = count($article_service->readAll());
        $articles = $article_service->search(array(
            'tag' => $request->query->get('tag')
        ));
        $tags = $tag_service->readAll();

        return $this->render('landing.html.twig', array(
            "articles" => $articles,
            "tags" => $tags,
            'article_count' => $article_count
        ));
    }
    /**
     * @Route("/{_locale}/article/{slug}", name="article_view_page")
     */
    public function articleAction(ArticleTranslation $article){
        return $this->render('article_view_page.html.twig', array(
            "article" => $article->getArticle()
        ));
    }
}
?>
