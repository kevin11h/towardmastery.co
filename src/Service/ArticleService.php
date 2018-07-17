<?php
namespace App\Service;

use App\Entity\Article;
use App\Entity\ArticleTranslation;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;

class ArticleService{

  private $em;
  private $uploader;

  public function __construct(
      EntityManagerInterface $em,
      FileUploader $uploader
  ){
    $this->em = $em;
    $this->uploader = $uploader;
  }

  public function create($args){

      $article = new Article();

      if(!empty($args['date'])){
          $article->setDate($args['date']);
      }
      if(!empty($args['status'])){
          $article->setStatus($args['status']);
      }
      if(!empty($args['cover'])){
          $cover_name = $this->uploader->upload($args['cover']);
          $article->setCover($cover_name);
      }

      foreach($args['translations'] as $translation){
          $article_translation = new ArticleTranslation();

          $article->addTranslation($article_translation);
          $article_translation->setArticle($article);

          if(!empty($translation['title'])){
              $article_translation->setTitle($translation['title']);
          }
          if(!empty($translation['content'])){
              $article_translation->setContent($translation['content']);
          }
          if(!empty($translation['language'])){
              $article_translation->setLanguage($translation['language']);
          }

          $this->em->persist($article_translation);
      }

      $this->em->persist($article);
      $this->em->flush();

      return $article;
  }

  public function update(
      Article $article,
      $args
  ){
      if(!empty($args['date'])){
          $article->setDate($args['date']);
      }
      if(!empty($args['status'])){
          $article->setStatus($args['status']);
      }
      if(!empty($args['cover'])){
          $cover_name = $this->uploader->upload($args['cover']);
          $article->setCover($cover_name);
      }

      foreach($args['translations'] as $translation){
          foreach($article->getTranslations() as $article_translation){
                if($article_translation->getId() == $translation['id']){
                    if(!empty($translation['title'])){
                        $article_translation->setTitle($translation['title']);
                    }
                    if(!empty($translation['content'])){
                        $article_translation->setContent($translation['content']);
                    }
                    if(!empty($translation['language'])){
                        $article_translation->setLanguage($translation['language']);
                    }

                    $this->em->persist($article_translation);
                }
          }
      }

      $this->em->persist($article);
      $this->em->flush();

      return $article;
  }

  public function readOne($id){
    $article = $this->em->getRepository(Article::class)->findOneById($id);
    return $article;
  }

  public function readOneBySlug($slug){
    $article_translation = $this->em->getRepository(ArticleTranslation::class)->findOneBySlug($slug);
    $article = $article_translation->getArticle();
    return $article;
  }

  public function readAll(){
    $qb = $this->em->createQueryBuilder();
    $qb->select('a')->from(Article::class, 'a')->orderBy('a.date', 'DESC');
    if(func_num_args() == 1){
      $qb->setMaxResults(func_get_arg(0));
    }
    $articles = $qb->getQuery()->getResult();
    return $articles;
  }

  public function search($filters, $orderby, $paging){
    $qb = $this->em->createQueryBuilder();
    $articles;
    if(!empty($filters)){
        $qb->select('a')->from(Article::class, 'a');
        $filtering = array();
        foreach($filters as $filter){
            $type = strtolower($filter['criteria']);
            $type = preg_replace('/\s+/', '', $type);
            array_push($filtering, $qb->expr()->eq('a.' . $type, $qb->expr()->literal($filter['filter'])));
        }
        $qb->where($qb->expr()->orX(...$filtering));
        if(!empty($orderby)){
          $type = strtolower($orderby['criteria']);
          $type = preg_replace('/\s+/', '', $type);
          $qb->orderBy('a.' . $type, $orderby['direction']);
        }
        if(!empty($paging)){
          $offset = ($paging['current_page']-1) * $paging['result_per_page'];
          $limit = $paging['result_per_page'];
          $qb->setFirstResult($offset)->setMaxResults($limit);
        }
        // dump($qb->getQuery());
        // die;
        $articles = $qb->getQuery()->getResult();
    } else{
      $articles = null;
    }
    // dump($qb);
    // die;
    return $articles;
  }

  public function delete($article){
      foreach($article->getTranslations() as $translation){
          $this->em->remove($translation);
      }

      $this->em->remove($article);
      $this->em->flush();

      return true;
  }

}

?>
