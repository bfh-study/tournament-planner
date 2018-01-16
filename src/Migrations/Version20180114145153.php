<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180114145153 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE schedules (game_number INT NOT NULL, tournament INT NOT NULL, home_team_name VARCHAR(60) NOT NULL, home_team_tournament_id INT NOT NULL, away_team_name VARCHAR(60) NOT NULL, away_team_tournament_id INT NOT NULL, date DATETIME NOT NULL, field INT NOT NULL, goal_home INT DEFAULT NULL, goal_away INT DEFAULT NULL, INDEX IDX_313BDC8EBD5FB8D9 (tournament), INDEX IDX_313BDC8EF116F33918761521 (home_team_name, home_team_tournament_id), INDEX IDX_313BDC8E10A2624397F34B91 (away_team_name, away_team_tournament_id), PRIMARY KEY(tournament, game_number)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8EBD5FB8D9 FOREIGN KEY (tournament) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8EF116F33918761521 FOREIGN KEY (home_team_name, home_team_tournament_id) REFERENCES teams (name, tournament_id)');
        $this->addSql('ALTER TABLE schedules ADD CONSTRAINT FK_313BDC8E10A2624397F34B91 FOREIGN KEY (away_team_name, away_team_tournament_id) REFERENCES teams (name, tournament_id)');
        $this->addSql('ALTER TABLE tournaments ADD fields INT NOT NULL, ADD duration INT NOT NULL, ADD interruption INT NOT NULL, ADD backround TINYINT(1) NOT NULL, CHANGE date date DATETIME NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE schedules');
        $this->addSql('ALTER TABLE tournaments DROP fields, DROP duration, DROP interruption, DROP backround, CHANGE date date DATE NOT NULL');
    }
}
