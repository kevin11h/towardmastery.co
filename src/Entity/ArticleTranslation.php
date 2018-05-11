<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="ArticleTranslation")
*/
class ArticleTranslation{

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
  *@ORM\Column(type="string", length=255)
  */
  private $slug;
  /**
  *@ORM\Column(type="text")
  */
  private $content;
  /**
  *@ORM\Column(type="string", length=5)
  */
  private $language;
  /**
   * @ORM\ManyToOne(targetEntity="Article", inversedBy="translations")
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

  public function getSlug(){
    return $this->slug;
  }

  public function getContent(){
    return $this->content;
  }

  public function getLanguage(){
    return $this->language;
  }

  public function getArticle(){
    return $this->article;
  }

  public function setArticle($article){
    $this->article = $article;
    return $this;
  }

  public function setTitle($title){
    $this->title = $title;
    $this->slug = $this->slugify($title);
    return $this;
  }

  public function setContent($content){
    $this->content = $content;
    return $this;
  }

  public function setLanguage($language){
    $this->language = $language;
    return $this;
  }

  public function toArray(){
    $array = array(
      'id' => $this->id,
      'title' => $this->title,
      'content' => $this->content,
      'language' => $this->language
    );

    return $array;
  }

  public function slugify($string){
        // replace non letter or digits by -
        $slug = preg_replace('#[^\\pL\d]+#u', '-', $string);
        // trim
        $slug = trim($slug, '-');
        // transliterate
        if (function_exists('iconv'))
        {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }
        // lowercase
        $slug = strtolower($slug);
        // remove unwanted characters
        $slug = preg_replace('#[^-\w]+#', '', $slug);
        if (empty($slug))
        {
            return 'n-a';
        }
        return $slug;
    }
}

?>
