<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

use Doctrine\ORM\EntityManagerInterface;

use App\Service\ArticleService;
use App\Service\FileUploader;
use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Form\ArticleType;

class AdminController extends Controller{
    /**
     * @Route("/admin", name="admin_landing_page")
     */
    public function adminLandingPage(){
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/configure/article", name="configure_article_list_page")
     */
    public function configureArticleListPage(ArticleService $article_service){
        $articles = $article_service->readAll();
        return $this->render('admin/configure/article_list.html.twig', array(
          'articles' => $articles
        ));
    }

    /**
     * @Route("/admin/configure/article/create", name="create_article")
     */
    public function createArticle(Request $request, ArticleService $article_service, EntityManagerInterface $em, FileUploader $uploader){

        $article = new Article();
        $article_english = new ArticleTranslation();
        $article_english->setLanguage('en');
        $article_french = new ArticleTranslation();
        $article_french->setLanguage('fr');
        $article->addTranslation($article_english);
        $article->addTranslation($article_french);
        $article_english->setArticle($article);
        $article_french->setArticle($article);
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $article->getAvatar();
            $cover = $article->getCover();
            $avatar_name = $uploader->upload($avatar);
            $cover_name = $uploader->upload($cover);
            $article->setAvatar($avatar_name);
            $article->setCover($cover_name);

            $em->flush();
            return $this->redirectToRoute('configure_article_list_page');
        }

        return $this->render('admin/configure/article_view.html.twig', array(
            'form' => $form->createView(),
            'article' => $article
        ));
    }

    /**
     * @Route("/admin/configure/article/edit/{id}", name="edit_article")
     */
    public function editArticle(Article $article, EntityManagerInterface $em, Request $request){

        $article->setAvatar(
          new File($this->getParameter('article_img_directory').'/'.$article->getAvatar())
        );
        $article->setCover(
          new File($this->getParameter('article_img_directory').'/'.$article->getCover())
        );
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $article->getAvatar();
            $cover = $article->getCover();
            $avatar_name = $uploader->upload($avatar);
            $cover_name = $uploader->upload($cover);
            $article->setAvatar($avatar_name);
            $article->setCover($cover_name);

            $em->flush();
            return $this->redirectToRoute('configure_article_list_page');
        }

        return $this->render('admin/configure/article_view.html.twig', array(
          'form' => $form->createView(),
          'article' => $article
        ));
    }

    /**
     * @Route("/admin/configure/article/delete/{id}", name="delete_article")
     */
    public function deleteArticle(Article $article, EntityManagerInterface $em){
        $article_english = $article->getEnglish();
        $article_french = $article->getFrench();

        $en_tags = $article_english->getTags();
        $fr_tags = $article_french->getTags();

        foreach($en_tags as $tag){
          $em->remove($tag);
        }

        foreach($fr_tags as $tag){
          $em->remove($tag);
        }

        $em->remove($article_english);
        $em->remove($article_french);
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('configure_article_list_page');
    }
}
?>
