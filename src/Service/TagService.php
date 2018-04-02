<?php
namespace App\Service;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;

class TagService{

  private $em;

  public function __construct(EntityManagerInterface $em){
    $this->em = $em;
  }

  public function readAll(){
    $qb = $this->em->createQueryBuilder();
    $qb->select('t.title')->from(Tag::class, 't')->distinct();
    $articles = $qb->getQuery()->getResult();
    return $articles;
  }

}

?>
