<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328003641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserver ADD event_id INT NOT NULL, DROP nomevent');
        $this->addSql('ALTER TABLE reserver ADD CONSTRAINT FK_B9765E9371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_B9765E9371F7E88B ON reserver (event_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserver DROP FOREIGN KEY FK_B9765E9371F7E88B');
        $this->addSql('DROP INDEX IDX_B9765E9371F7E88B ON reserver');
        $this->addSql('ALTER TABLE reserver ADD nomevent VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP event_id');
    }
}
