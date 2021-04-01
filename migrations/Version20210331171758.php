<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210331171758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, prix INT NOT NULL, quantity INT NOT NULL, nbrmatrres INT NOT NULL, availability TINYINT(1) DEFAULT \'1\', image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material_reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, Material_id INT DEFAULT NULL, INDEX IDX_E1D47B5DA76ED395 (user_id), INDEX IDX_E1D47B5D659423B8 (Material_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, sexe VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, phone INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, activation_token VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE material_reservation ADD CONSTRAINT FK_E1D47B5DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE material_reservation ADD CONSTRAINT FK_E1D47B5D659423B8 FOREIGN KEY (Material_id) REFERENCES material (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE material_reservation DROP FOREIGN KEY FK_E1D47B5D659423B8');
        $this->addSql('ALTER TABLE material_reservation DROP FOREIGN KEY FK_E1D47B5DA76ED395');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE material_reservation');
        $this->addSql('DROP TABLE `user`');
    }
}
