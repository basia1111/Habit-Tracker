<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328210949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habit ADD monday TINYINT(1) NOT NULL, ADD tusday TINYINT(1) NOT NULL, ADD wednesday TINYINT(1) NOT NULL, ADD thursday TINYINT(1) NOT NULL, ADD friday TINYINT(1) NOT NULL, ADD sathurday TINYINT(1) NOT NULL, ADD sunday TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habit DROP monday, DROP tusday, DROP wednesday, DROP thursday, DROP friday, DROP sathurday, DROP sunday');
    }
}
