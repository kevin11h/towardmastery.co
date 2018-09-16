<?php
namespace App\Service;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\ArticleTranslation;

use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use App\Service\TagService;

class ArticleService{

  private $em;
  private $uploader;
   private $tag_service;

  public function __construct(
      EntityManagerInterface $em,
      FileUploader $uploader,
      TagService $tag_service
  ){
    $this->em = $em;
    $this->uploader = $uploader;
    $this->tag_service = $tag_service;
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

      if(!empty($args['tags'])){
          foreach($args['tags'] as $tag){
              $current_tag = $this->tag_service->readOneBySlug($tag);
              $this->addTag($article, $current_tag);
          }
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
      if(!empty($args['translations'])){
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
      }

      foreach($article->getTags() as $tag){
          $this->removeTag($article, $tag);
      }

      if(!empty($args['tags'])){
          foreach($args['tags'] as $tag){
              $current_tag = $this->tag_service->readOneBySlug($tag);
              $this->addTag($article, $current_tag);
          }
      }

      $this->em->persist($article);
      $this->em->flush();

      return $article;
  }

  public function addTag(Article $article, Tag $tag){
      $article->addTag($tag);
      $tag->addArticle($article);
      $this->em->persist($tag);
  }

  public function removeTag(Article $article, Tag $tag){
      $article->removeTag($tag);
      $tag->removeArticle($article);
      $this->em->persist($tag);
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

  public function search($args){
    $qb = $this->em->createQueryBuilder();
    $qb->select('a')->from(Article::class, 'a');
    if(!empty($args['tag'])){
        $qb->join('a.tags', 'at')->join('at.translations', 'att');
        $qb->where('att.slug = :slug')->setParameter('slug', $args['tag']);
    }
    $qb->orderby('a.date', 'DESC');
    $articles = $qb->getQuery()->getResult();
    return $articles;
  }

  public function delete($article){
      foreach($article->getTranslations() as $translation){
          $this->em->remove($translation);
      }

      foreach($article->getTags() as $tag){
          $this->removeTag($article, $tag);
      }

      $this->em->remove($article);
      $this->em->flush();

      return true;
  }

}

?>
