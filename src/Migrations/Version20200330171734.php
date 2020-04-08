<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200330171734 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE serveur ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE serveur ADD CONSTRAINT FK_77CC53A6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_77CC53A6A76ED395 ON serveur (user_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE alliance CHANGE serveur_id serveur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE troupe CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lost_password CHANGE token token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE alliance CHANGE serveur_id serveur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lost_password CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE serveur DROP FOREIGN KEY FK_77CC53A6A76ED395');
        $this->addSql('DROP INDEX IDX_77CC53A6A76ED395 ON serveur');
        $this->addSql('ALTER TABLE serveur DROP user_id');
        $this->addSql('ALTER TABLE troupe CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE username username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
