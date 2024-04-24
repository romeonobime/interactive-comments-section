<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423160136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_users_liked (comment_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1682AEB1F8697D13 (comment_id), INDEX IDX_1682AEB1A76ED395 (user_id), PRIMARY KEY(comment_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_users_disliked (comment_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DD2201C5F8697D13 (comment_id), INDEX IDX_DD2201C5A76ED395 (user_id), PRIMARY KEY(comment_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_users_liked ADD CONSTRAINT FK_1682AEB1F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_users_liked ADD CONSTRAINT FK_1682AEB1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_users_disliked ADD CONSTRAINT FK_DD2201C5F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_users_disliked ADD CONSTRAINT FK_DD2201C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_users_liked DROP FOREIGN KEY FK_1682AEB1F8697D13');
        $this->addSql('ALTER TABLE comment_users_liked DROP FOREIGN KEY FK_1682AEB1A76ED395');
        $this->addSql('ALTER TABLE comment_users_disliked DROP FOREIGN KEY FK_DD2201C5F8697D13');
        $this->addSql('ALTER TABLE comment_users_disliked DROP FOREIGN KEY FK_DD2201C5A76ED395');
        $this->addSql('DROP TABLE comment_users_liked');
        $this->addSql('DROP TABLE comment_users_disliked');
    }
}
