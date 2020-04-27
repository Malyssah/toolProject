<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424172014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE peuple');
        $this->addSql('ALTER TABLE alliance CHANGE serveur_id serveur_id INT DEFAULT NULL, CHANGE chef_alliance chef_alliance VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lost_password CHANGE token token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE troupe CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE serveur_id serveur_id INT DEFAULT NULL, CHANGE alliance_id alliance_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL, CHANGE peuple peuple VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE peuple (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE alliance CHANGE serveur_id serveur_id INT DEFAULT NULL, CHANGE chef_alliance chef_alliance VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lost_password CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE troupe CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE serveur_id serveur_id INT DEFAULT NULL, CHANGE alliance_id alliance_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE username username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE peuple peuple VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
