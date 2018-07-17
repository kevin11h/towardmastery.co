<?php
namespace App\Service;

use App\Entity\Tag;
use App\Entity\TagTranslation;
use Doctrine\ORM\EntityManagerInterface;

class TagService{

  private $em;

  public function __construct(EntityManagerInterface $em){
    $this->em = $em;
  }

  public function create($args){

      $tag = new Tag();

      foreach($args['translations'] as $translation){
          $tag_translation = new TagTranslation();

          $tag->addTranslation($tag_translation);
          $tag_translation->setTag($tag);

          if(!empty($translation['name'])){
              $tag_translation->setName($translation['name']);
          }
          if(!empty($translation['language'])){
              $tag_translation->setLanguage($translation['language']);
          }

          $this->em->persist($tag_translation);
      }

      $this->em->persist($tag);
      $this->em->flush();

      return $tag;
  }

  // public function readOne($id){
  //   $tag = $this->em->getRepository(Tag::class)->findOneById($id);
  //   return $tag;
  // }

  public function readOneBySlug($slug){
    $tag_translation = $this->em->getRepository(TagTranslation::class)->findOneBySlug($slug);
    $tag = $tag_translation->getTag();
    return $tag;
  }

  public function readAll(){
    $qb = $this->em->createQueryBuilder();
    $qb->select('a')->from(Tag::class, 'a')->distinct('a.id');
    if(func_num_args() == 1){
      $qb->setMaxResults(func_get_arg(0));
    }
    $tags = $qb->getQuery()->getResult();
    return $tags;
  }

  // public function search($filters, $orderby, $paging){
  //   $qb = $this->em->createQueryBuilder();
  //   $tags;
  //   if(!empty($filters)){
  //       $qb->select('a')->from(Tag::class, 'a');
  //       $filtering = array();
  //       foreach($filters as $filter){
  //           $type = strtolower($filter['criteria']);
  //           $type = preg_replace('/\s+/', '', $type);
  //           array_push($filtering, $qb->expr()->eq('a.' . $type, $qb->expr()->literal($filter['filter'])));
  //       }
  //       $qb->where($qb->expr()->orX(...$filtering));
  //       if(!empty($orderby)){
  //         $type = strtolower($orderby['criteria']);
  //         $type = preg_replace('/\s+/', '', $type);
  //         $qb->orderBy('a.' . $type, $orderby['direction']);
  //       }
  //       if(!empty($paging)){
  //         $offset = ($paging['current_page']-1) * $paging['result_per_page'];
  //         $limit = $paging['result_per_page'];
  //         $qb->setFirstResult($offset)->setMaxResults($limit);
  //       }
  //       $tags = $qb->getQuery()->getResult();
  //   } else{
  //     $tags = null;
  //   }
  //   return $tags;
  // }

  public function update(Tag $tag, $args){
      foreach($args['translations'] as $translation){
          foreach($tag->getTranslations() as $tag_translation){
              if($tag_translation->getId() == $translation['id']){
                  if(!empty($translation['name'])){
                      $tag_translation->setName($translation['name']);
                  }
                  if(!empty($translation['language'])){
                      $tag_translation->setLanguage($translation['language']);
                  }

                  $this->em->persist($tag_translation);
              }
          }
      }

      $this->em->persist($tag);
      $this->em->flush();

      return $tag;
  }

  public function delete(Tag $tag){
      foreach($tag->getTranslations() as $translation){
          $this->em->remove($translation);
      }

      $this->em->remove($tag);
      $this->em->flush();

      return true;
  }

}

?>
