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
     * @Route("/{_locale}", name="landing_page", requirements = {"_locale": "en|fr"})
     */
    public function landingPage(
        $_locale = 'en',
        ArticleService $article_service,
        TagService $tag_service,
        Request $request
    ){
        $all_articles = $article_service->readAll();
        $tags = $tag_service->readAll();

        $articles = array();
        if(!empty($request->query->get('tag'))){
            $slug = $request->query->get('tag');

            foreach($all_articles as $article){
                foreach($article->getTags() as $tag){
                    if($tag->getTranslations()[0]->getSlug() == $slug){
                        array_push($articles, $article);
                    }
                }
            }
        } else {
            $articles = $all_articles;
        }

        return $this->render('landing.html.twig', array(
            "all_articles" => $all_articles,
            "articles" => $articles,
            "tags" => $tags
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
