<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180114102413 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE schedule (game_number INT NOT NULL, date DATETIME NOT NULL, field INT NOT NULL, goal_home INT NOT NULL, goal_away INT NOT NULL, PRIMARY KEY(game_number, date)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournaments ADD fields INT NOT NULL, ADD duration INT NOT NULL, ADD interruption INT NOT NULL, ADD backround TINYINT(1) NOT NULL, CHANGE date date DATETIME NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE schedule');
        $this->addSql('ALTER TABLE tournaments DROP fields, DROP duration, DROP interruption, DROP backround, CHANGE date date DATE NOT NULL');
    }
}
