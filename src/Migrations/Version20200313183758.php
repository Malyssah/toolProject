<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313183758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE serveur_user');
        $this->addSql('ALTER TABLE troupe CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE serveur_user (serveur_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EA2EC3DA76ED395 (user_id), INDEX IDX_EA2EC3DB8F06499 (serveur_id), PRIMARY KEY(serveur_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE serveur_user ADD CONSTRAINT FK_EA2EC3DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE serveur_user ADD CONSTRAINT FK_EA2EC3DB8F06499 FOREIGN KEY (serveur_id) REFERENCES serveur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE troupe CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE username username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
