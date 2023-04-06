<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329215849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE execution ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE execution ADD CONSTRAINT FK_2A0D73AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_2A0D73AA76ED395 ON execution (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE execution DROP FOREIGN KEY FK_2A0D73AA76ED395');
        $this->addSql('DROP INDEX IDX_2A0D73AA76ED395 ON execution');
        $this->addSql('ALTER TABLE execution DROP user_id');
    }
}
