<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330183419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE execution ADD habit_id INT NOT NULL');
        $this->addSql('ALTER TABLE execution ADD CONSTRAINT FK_2A0D73AE7AEB3B2 FOREIGN KEY (habit_id) REFERENCES habit (id)');
        $this->addSql('CREATE INDEX IDX_2A0D73AE7AEB3B2 ON execution (habit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE execution DROP FOREIGN KEY FK_2A0D73AE7AEB3B2');
        $this->addSql('DROP INDEX IDX_2A0D73AE7AEB3B2 ON execution');
        $this->addSql('ALTER TABLE execution DROP habit_id');
    }
}
