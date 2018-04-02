<?php
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\ArticleTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// php bin/console doctrine:fixtures:load

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      for($i = 0; $i < 20; $i++){
        $article = new Article();
        $article->setStatus('public');
        $article->setAvatar('background.jpg');
        $article->setCover('cover_test.jpeg');

        $article_fr = new ArticleTranslation();
        $article_en = new ArticleTranslation();

        $article_fr->setTitle($i . " La vie est un long fleuve tranquille.")->setLanguage('fr')->setDescription("Un court texte expliquant le début de la vie. Synthèse, Antithèse, Thèse. Et pas l'inverse.");
        $article_en->setTitle($i . " La vie est un long fleuve tranquille.")->setLanguage('en')->setDescription("Un court texte expliquant le début de la vie. Synthèse, Antithèse, Thèse. Et pas l'inverse.");

        $text = file_get_contents(__DIR__.'/loremipsum.txt');
        $article_fr->setContent($text);
        $article_en->setContent($text);
        $tag_fr = new Tag();
        $tag_fr->setTitle('FR');
        $tag_fr->setArticle($article_fr);
        $tag_en = new Tag();
        $tag_en->setTitle('EN');
        $tag_en->setArticle($article_en);
        $article_fr->addTag($tag_fr);
        $article_en->addTag($tag_en);


        $article->addTranslation($article_en);
        $article->addTranslation($article_fr);
        $article_fr->setArticle($article);
        $article_en->setArticle($article);

        $manager->persist($article);
      }

      $manager->flush();
    }
}
?>
