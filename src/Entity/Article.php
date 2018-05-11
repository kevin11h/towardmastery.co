<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity
* @ORM\Table(name="Article")
*/
class Article{
    /**
    *@ORM\Column(type="integer")
    *@ORM\Id
    *@ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    /**
    *@ORM\Column(type="datetime", nullable=true)
    */
    private $date;
    /**
    *@ORM\Column(type="string", length=255)
    */
    private $status;
    /**
    *@ORM\Column(type="string", length=255)
    * @Assert\Image
    */
    private $cover;
    /**
     * @ORM\OneToMany(targetEntity="ArticleTranslation", mappedBy="article", cascade="persist")
     */
    private $translations;

    public function __construct(){
      $this->translations = new ArrayCollection();
      $this->date = new \DateTime();
    }

    public function getId(){
      return $this->id;
    }

    public function getStatus(){
      return $this->status;
    }

    public function getDate(){
      return $this->date;
    }

    public function getCover(){
        return $this->cover;
    }

    public function getTranslations(){
      return $this->translations;
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

    public function setDate($date){
      $this->date = $date;
      return $this;
    }

    public function setStatus($status){
      $this->status = $status;
      return $this;
    }

    public function setCover($cover){
      $this->cover = $cover;
      return $this;
    }

    public function addTranslation($translation){
      $this->translations->add($translation);
      return $this;
    }

    public function toArray(){

      $translation_array = array();
      foreach($this->translations as $translation){
        array_push($translation_array, $translation->toArray());
      }

      $array = array(
        'id' => $this->id,
        'date' => $this->date,
        'status' => $this->status,
        'cover' => $this->cover,
        'translations' => $translation_array
      );

      return $array;
    }
}

?>
