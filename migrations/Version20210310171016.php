<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210310171016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_materiel DROP FOREIGN KEY FK_8567528516880AAF');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix INT NOT NULL, availability TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE reservation_materiel');
        $this->addSql('ALTER TABLE material_reservation ADD CONSTRAINT FK_E1D47B5DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE material_reservation ADD CONSTRAINT FK_E1D47B5D659423B8 FOREIGN KEY (Material_id) REFERENCES material (id)');
        $this->addSql('ALTER TABLE user ADD password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material_reservation DROP FOREIGN KEY FK_E1D47B5D659423B8');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, disponibility TINYINT(1) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_materiel (id INT AUTO_INCREMENT NOT NULL, materiel_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date_reservation DATETIME NOT NULL, INDEX IDX_85675285A76ED395 (user_id), INDEX IDX_8567528516880AAF (materiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation_materiel ADD CONSTRAINT FK_8567528516880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE reservation_materiel ADD CONSTRAINT FK_85675285A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE material');
        $this->addSql('ALTER TABLE material_reservation DROP FOREIGN KEY FK_E1D47B5DA76ED395');
        $this->addSql('ALTER TABLE `user` DROP password');
    }
}
