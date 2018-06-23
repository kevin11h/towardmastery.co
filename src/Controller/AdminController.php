<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Service\ArticleService;
use App\Service\FileUploader;
use App\Entity\Article;
use App\Entity\ArticleTranslation;
use App\Form\ArticleType;

class AdminController extends Controller{

    /**
     * @Route("/admin", name="configure_article_list_page")
     * @Security("has_role('ROLE_USER')")
     */
    public function configureArticleListPage(ArticleService $article_service){
        $articles = $article_service->readAll();
        return $this->render('admin/configure/article_list.html.twig', array(
          'articles' => $articles
        ));
    }

    /**
     * @Route("/admin/article/create", name="create_article")
     */
    public function createArticle(Request $request, ArticleService $article_service, EntityManagerInterface $em, FileUploader $uploader){

        $article = new Article();
        $article_english = new ArticleTranslation();
        $article_english->setLanguage('en');
        // $article_french = new ArticleTranslation();
        // $article_french->setLanguage('fr');
        $article->addTranslation($article_english);
        // $article->addTranslation($article_french);
        $article_english->setArticle($article);
        // $article_french->setArticle($article);
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover = $article->getCover();
            $cover_name = $uploader->upload($cover);
            $article->setCover($cover_name);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('configure_article_list_page');
        }

        return $this->render('admin/configure/article_view.html.twig', array(
            'form' => $form->createView(),
            'article' => $article
        ));
    }

    /**
     * @Route("/admin/article/edit/{id}", name="edit_article")
     */
    public function editArticle(Article $article, EntityManagerInterface $em, Request $request, FileUploader $uploader){
        $previous_cover = $article->getCover();
        $article->setCover(
          new File($this->getParameter('article_img_directory').'/'.$article->getCover())
        );
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover = $article->getCover();
            if(!empty($cover)){
                $cover_name = $uploader->upload($cover);
            } else {
                $cover_name = $previous_cover;
            }
            $article->setCover($cover_name);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('configure_article_list_page');
        }

        return $this->render('admin/configure/article_view.html.twig', array(
          'form' => $form->createView(),
          'article' => $article
        ));
    }

    /**
     * @Route("/admin/article/delete/{id}", name="delete_article")
     */
    public function deleteArticle(Article $article, EntityManagerInterface $em){
        $article_english = $article->getEnglish();
        // $article_french = $article->getFrench();

        // $en_tags = $article_english->getTags();
        // $fr_tags = $article_french->getTags();

        // foreach($en_tags as $tag){
        //   $em->remove($tag);
        // }
        //
        // foreach($fr_tags as $tag){
        //   $em->remove($tag);
        // }

        $em->remove($article_english);
        // $em->remove($article_french);
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('configure_article_list_page');
    }

    /**
     * @Route("/login", name="login_page")
     */
     public function renderLoginPage(AuthenticationUtils $auth_utils, Request $request)
     {
         $error = $auth_utils->getLastAuthenticationError();
         // last username entered by the user
         $last_username = $auth_utils->getLastUsername();
         return $this->render('login.html.twig', array(
             'last_username' => $last_username,
             'error' => $error
         ));
     }
     /**
      * @Route("/logout", name="logout_page")
      */
     public function renderLogoutPage(): void
     {
         throw new \Exception('This should never be reached!');
     }
}
?>
