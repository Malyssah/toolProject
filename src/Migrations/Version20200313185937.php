<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313185937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE username username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE troupe DROP FOREIGN KEY FK_81FBFC14A76ED395');
        $this->addSql('DROP INDEX UNIQ_81FBFC14A76ED395 ON troupe');
        $this->addSql('ALTER TABLE troupe ADD serveur_id INT NOT NULL, CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL, CHANGE user_id users_id INT NOT NULL');
        $this->addSql('ALTER TABLE troupe ADD CONSTRAINT FK_81FBFC1467B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE troupe ADD CONSTRAINT FK_81FBFC14B8F06499 FOREIGN KEY (serveur_id) REFERENCES serveur (id)');
        $this->addSql('CREATE INDEX IDX_81FBFC1467B3B43D ON troupe (users_id)');
        $this->addSql('CREATE INDEX IDX_81FBFC14B8F06499 ON troupe (serveur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE troupe DROP FOREIGN KEY FK_81FBFC1467B3B43D');
        $this->addSql('ALTER TABLE troupe DROP FOREIGN KEY FK_81FBFC14B8F06499');
        $this->addSql('DROP INDEX IDX_81FBFC1467B3B43D ON troupe');
        $this->addSql('DROP INDEX IDX_81FBFC14B8F06499 ON troupe');
        $this->addSql('ALTER TABLE troupe ADD user_id INT NOT NULL, DROP users_id, DROP serveur_id, CHANGE imperian imperian INT DEFAULT NULL, CHANGE caesaris caesaris INT DEFAULT NULL, CHANGE belier belier INT DEFAULT NULL, CHANGE catapulte catapulte INT DEFAULT NULL, CHANGE phalange phalange INT DEFAULT NULL, CHANGE druide druide INT DEFAULT NULL, CHANGE gourdin gourdin INT DEFAULT NULL, CHANGE teuton teuton INT DEFAULT NULL');
        $this->addSql('ALTER TABLE troupe ADD CONSTRAINT FK_81FBFC14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81FBFC14A76ED395 ON troupe (user_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE username username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
