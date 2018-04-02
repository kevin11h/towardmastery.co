<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="Tag")
*/
class Tag{
    /**
    *@ORM\Column(type="integer")
    *@ORM\Id
    *@ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    /**
    *@ORM\Column(type="string", length=255)
    */
    private $title;
    /**
     * @ORM\ManyToOne(targetEntity="ArticleTranslation", inversedBy="tags")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article;

    public function __construct(){
    }

    public function getId(){
      return $this->id;
    }

    public function getTitle(){
      return $this->title;
    }

    public function setTitle($title){
      $this->title = $title;
      return $this;
    }

    public  function setArticle($article){
      $this->article = $article;
      return $this;
    }
}

?>
