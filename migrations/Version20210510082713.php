<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510082713 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C71F7E88B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C71F7E88B ON comment');
        $this->addSql('ALTER TABLE comment ADD idmateriel_id INT DEFAULT NULL, ADD idclient_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, DROP event_id, DROP user_id, DROP creation_date');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD0497A00 FOREIGN KEY (idmateriel_id) REFERENCES materiel (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C67F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_9474526CD0497A00 ON comment (idmateriel_id)');
        $this->addSql('CREATE INDEX IDX_9474526C67F0C0D4 ON comment (idclient_id)');
        $this->addSql('ALTER TABLE materiel ADD idclient_id INT DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09167F0C0D4 FOREIGN KEY (idclient_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_18D2B09167F0C0D4 ON materiel (idclient_id)');
        $this->addSql('ALTER TABLE reservation CHANGE approuve approuve TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE subject CHANGE views views INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CD0497A00');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C67F0C0D4');
        $this->addSql('DROP INDEX IDX_9474526CD0497A00 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C67F0C0D4 ON comment');
        $this->addSql('ALTER TABLE comment ADD event_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD creation_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, DROP idmateriel_id, DROP idclient_id, DROP created_at');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C71F7E88B ON comment (event_id)');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09167F0C0D4');
        $this->addSql('DROP INDEX IDX_18D2B09167F0C0D4 ON materiel');
        $this->addSql('ALTER TABLE materiel ADD image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP idclient_id, DROP photo');
        $this->addSql('ALTER TABLE reservation CHANGE approuve approuve TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE subject CHANGE views views INT DEFAULT NULL');
    }
}
