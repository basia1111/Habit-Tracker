<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328210339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habit DROP monday, DROP tuesday, DROP wednesday, DROP thursday, DROP friday, DROP sathurday, DROP sunday');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habit ADD monday VARCHAR(255) NOT NULL, ADD tuesday VARCHAR(255) NOT NULL, ADD wednesday VARCHAR(255) NOT NULL, ADD thursday VARCHAR(255) NOT NULL, ADD friday VARCHAR(255) NOT NULL, ADD sathurday VARCHAR(255) NOT NULL, ADD sunday VARCHAR(255) NOT NULL');
    }
}
