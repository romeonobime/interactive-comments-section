<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416133054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', score INT NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reply (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', score INT NOT NULL, replying_to VARCHAR(255) NOT NULL, INDEX IDX_FDA8C6E0A76ED395 (user_id), INDEX IDX_FDA8C6E0F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reply ADD CONSTRAINT FK_FDA8C6E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reply ADD CONSTRAINT FK_FDA8C6E0F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE reply DROP FOREIGN KEY FK_FDA8C6E0A76ED395');
        $this->addSql('ALTER TABLE reply DROP FOREIGN KEY FK_FDA8C6E0F8697D13');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE reply');
    }
}
