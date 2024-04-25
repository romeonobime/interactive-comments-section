<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425101448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reply_users_liked (reply_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AF6C563E8A0E4E7F (reply_id), INDEX IDX_AF6C563EA76ED395 (user_id), PRIMARY KEY(reply_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reply_users_disliked (reply_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AC9CF90B8A0E4E7F (reply_id), INDEX IDX_AC9CF90BA76ED395 (user_id), PRIMARY KEY(reply_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reply_users_liked ADD CONSTRAINT FK_AF6C563E8A0E4E7F FOREIGN KEY (reply_id) REFERENCES reply (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reply_users_liked ADD CONSTRAINT FK_AF6C563EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reply_users_disliked ADD CONSTRAINT FK_AC9CF90B8A0E4E7F FOREIGN KEY (reply_id) REFERENCES reply (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reply_users_disliked ADD CONSTRAINT FK_AC9CF90BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reply_users_liked DROP FOREIGN KEY FK_AF6C563E8A0E4E7F');
        $this->addSql('ALTER TABLE reply_users_liked DROP FOREIGN KEY FK_AF6C563EA76ED395');
        $this->addSql('ALTER TABLE reply_users_disliked DROP FOREIGN KEY FK_AC9CF90B8A0E4E7F');
        $this->addSql('ALTER TABLE reply_users_disliked DROP FOREIGN KEY FK_AC9CF90BA76ED395');
        $this->addSql('DROP TABLE reply_users_liked');
        $this->addSql('DROP TABLE reply_users_disliked');
    }
}
