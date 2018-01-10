<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171230161832 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE teams (name VARCHAR(60) NOT NULL, tournament_id INT NOT NULL, INDEX IDX_96C2225833D1A3E7 (tournament_id), PRIMARY KEY(name, tournament_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport_types (name VARCHAR(30) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournaments (id INT AUTO_INCREMENT NOT NULL, creator VARCHAR(60) DEFAULT NULL, sport_type_name VARCHAR(30) DEFAULT NULL, name VARCHAR(60) NOT NULL, place VARCHAR(30) NOT NULL, date DATE NOT NULL, INDEX IDX_E4BCFAC3BC06EA63 (creator), INDEX IDX_E4BCFAC3A2AE4363 (sport_type_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE teams ADD CONSTRAINT FK_96C2225833D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC3BC06EA63 FOREIGN KEY (creator) REFERENCES users (email)');
        $this->addSql('ALTER TABLE tournaments ADD CONSTRAINT FK_E4BCFAC3A2AE4363 FOREIGN KEY (sport_type_name) REFERENCES sport_types (name)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tournaments DROP FOREIGN KEY FK_E4BCFAC3A2AE4363');
        $this->addSql('ALTER TABLE teams DROP FOREIGN KEY FK_96C2225833D1A3E7');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE sport_types');
        $this->addSql('DROP TABLE tournaments');
    }
}
