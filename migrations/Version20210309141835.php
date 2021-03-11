<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210309141835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reserver (id INT AUTO_INCREMENT NOT NULL, iduser VARCHAR(255) NOT NULL, idevent VARCHAR(255) NOT NULL, nbrplace VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event DROP active');
        $this->addSql('ALTER TABLE inscription_event ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reserver');
        $this->addSql('ALTER TABLE event ADD active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE inscription_event DROP image');
        $this->addSql('ALTER TABLE user DROP first_name');
    }
}
