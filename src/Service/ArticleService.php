<?php
namespace App\Service;

use App\Entity\Article;
use App\Entity\ArticleTranslation;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService{

  private $em;

  public function __construct(EntityManagerInterface $em){
    $this->em = $em;
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

}

?>
