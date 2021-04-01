<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329172709 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD prix DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE iduser iduser VARCHAR(255) NOT NULL, CHANGE event event INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553BAE0AA7 FOREIGN KEY (event) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_42C849553BAE0AA7 ON reservation (event)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP prix');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553BAE0AA7');
        $this->addSql('DROP INDEX IDX_42C849553BAE0AA7 ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE event event VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE iduser iduser VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
