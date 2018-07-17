<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Entity
* @ORM\Table(name="TagTranslation")
*/
class TagTranslation{

  /**
  *@ORM\Column(type="integer")
  *@ORM\Id
  *@ORM\GeneratedValue(strategy="AUTO")
  */
  private $id;
  /**
  *@ORM\Column(type="string", length=255)
  */
  private $name;
  /**
  *@ORM\Column(type="string", length=255)
  */
  private $slug;
  /**
  *@ORM\Column(type="string", length=5)
  */
  private $language;
  /**
   * @ORM\ManyToOne(targetEntity="Tag", inversedBy="translations")
   * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
   */
  private $tag;

  // GETTERS

  public function getId(){
    return $this->id;
  }

  public function getName(){
    return $this->name;
  }

  public function getSlug(){
    return $this->slug;
  }

  public function getLanguage(){
    return $this->language;
  }

  public function getTag(){
    return $this->tag;
  }

  // SETTERS

  public function setTag($tag){
    $this->tag = $tag;
    return $this;
  }

  public function setName($name){
    $this->name = $name;
    $this->slug = $this->slugify($name);
    return $this;
  }

  public function setLanguage($language){
    $this->language = $language;
    return $this;
  }

  public function toArray(){
    $array = array(
      'id' => $this->id,
      'name' => $this->name,
      'slug' => $this->slug,
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
