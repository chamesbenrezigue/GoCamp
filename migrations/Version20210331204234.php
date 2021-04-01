<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331204234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, idmateriel_id INT DEFAULT NULL, idclient_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526CD0497A00 (idmateriel_id), INDEX IDX_9474526C67F0C0D4 (idclient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD0497A00 FOREIGN KEY (idmateriel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C67F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE materiel ADD idclient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09167F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_18D2B09167F0C0D4 ON materiel (idclient_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C67F0C0D4');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09167F0C0D4');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP INDEX IDX_18D2B09167F0C0D4 ON materiel');
        $this->addSql('ALTER TABLE materiel DROP idclient_id');
    }
}
