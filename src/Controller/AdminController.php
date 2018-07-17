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
    public function createArticle(
        Request $request,
        ArticleService $article_service
    ){

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

            $article = $article_service->create(array(
                'date' => $article->getDate(),
                'status' => $article->getStatus(),
                'cover' => $article->getCover(),
                'translations' => array(
                    'title' => $article->getTranslations()[0]->getTitle(),
                    'content' => $article->getTranslations()[0]->getContent(),
                    'language' => 'en'
                ),
            ));

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
    public function editArticle(
        Article $article,
        Request $request,
        ArticleService $article_service
    ){
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

            $article = $this->article_service->update($article, array(
                'cover' => $cover_name
            ));

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
    public function deleteArticle(
        Article $article, 
        ArticleService $article_service
    ){
        $this->article_service->delete($article);
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
