<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToMany(targetEntity="TagTranslation", mappedBy="tag", cascade="persist")
     */
    private $translations;

    /**
    * @ORM\ManyToMany(targetEntity="Article", inversedBy="tags")
    * @ORM\JoinTable(name="articles_tags")
    */
    private $articles;

    public function __construct(){
      $this->translations = new ArrayCollection();
      $this->articles = new ArrayCollection();
    }

    // GETTERS

    public function getId(){
      return $this->id;
    }

    public function getTranslations(){
      return $this->translations;
    }

    public function getArticles(){
      return $this->articles;
    }

    public function getEnglish(){
      foreach($this->translations as $translation){
        if($translation->getLanguage() == 'en'){
          return $translation;
        }
      }
      return false;
    }

    public function getFrench(){
      foreach($this->translations as $translation){
        if($translation->getLanguage() == 'fr'){
          return $translation;
        }
      }
      return false;
    }

    // SETTERS

    public function addTranslation($translation){
      $this->translations->add($translation);
      return $this;
    }

    public function addArticle($article){
      $this->articles->add($article);
      return $this;
    }

    public function removeArticle($article){
      $this->articles->removeElement($article);
      return $this;
    }

    public function toArray(){

      $translation_array = array();
      foreach($this->translations as $translation){
        array_push($translation_array, $translation->toArray());
      }

      $articles = array();
      foreach($this->articles as $article){
        array_push($articles, $article->toArrayWithoutTags());
      }

      $array = array(
        'id' => $this->id,
        'translations' => $translation_array,
        'articles' => $articles
      );

      return $array;
    }
}

?>
