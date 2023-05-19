<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519124746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE businesshours ADD open_time_morning INT NOT NULL, ADD closed_time_morning INT NOT NULL, ADD open_time_afternoon INT NOT NULL, ADD closed_time_afternoon INT NOT NULL, DROP open_time, DROP closed_time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE businesshours ADD open_time INT NOT NULL, ADD closed_time INT NOT NULL, DROP open_time_morning, DROP closed_time_morning, DROP open_time_afternoon, DROP closed_time_afternoon');
    }
}
