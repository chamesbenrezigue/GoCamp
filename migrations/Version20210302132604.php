<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302132604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_materiel ADD materiel_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation_materiel ADD CONSTRAINT FK_8567528516880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE reservation_materiel ADD CONSTRAINT FK_85675285A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_8567528516880AAF ON reservation_materiel (materiel_id)');
        $this->addSql('CREATE INDEX IDX_85675285A76ED395 ON reservation_materiel (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_materiel DROP FOREIGN KEY FK_8567528516880AAF');
        $this->addSql('ALTER TABLE reservation_materiel DROP FOREIGN KEY FK_85675285A76ED395');
        $this->addSql('DROP INDEX IDX_8567528516880AAF ON reservation_materiel');
        $this->addSql('DROP INDEX IDX_85675285A76ED395 ON reservation_materiel');
        $this->addSql('ALTER TABLE reservation_materiel DROP materiel_id, DROP user_id');
    }
}
