<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171212061116 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Comment (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, content LONGTEXT NOT NULL, INDEX IDX_5BC96BF07294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Tag (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_3BC4F1637294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Article (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, canonical_date DATETIME DEFAULT NULL, date DATETIME DEFAULT NULL, status VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ArticleTranslation (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, language VARCHAR(5) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_302153717294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF07294869C FOREIGN KEY (article_id) REFERENCES Article (id)');
        $this->addSql('ALTER TABLE Tag ADD CONSTRAINT FK_3BC4F1637294869C FOREIGN KEY (article_id) REFERENCES ArticleTranslation (id)');
        $this->addSql('ALTER TABLE ArticleTranslation ADD CONSTRAINT FK_302153717294869C FOREIGN KEY (article_id) REFERENCES Article (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF07294869C');
        $this->addSql('ALTER TABLE ArticleTranslation DROP FOREIGN KEY FK_302153717294869C');
        $this->addSql('ALTER TABLE Tag DROP FOREIGN KEY FK_3BC4F1637294869C');
        $this->addSql('DROP TABLE Comment');
        $this->addSql('DROP TABLE Tag');
        $this->addSql('DROP TABLE Article');
        $this->addSql('DROP TABLE ArticleTranslation');
    }
}
