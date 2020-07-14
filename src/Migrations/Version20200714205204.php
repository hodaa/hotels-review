<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200714205204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE revies (id INT AUTO_INCREMENT NOT NULL, no VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE review DROP hotel_id');
        $this->addSql('ALTER TABLE hotel ADD revies_id INT NOT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED9B14C01CC FOREIGN KEY (revies_id) REFERENCES revies (id)');
        $this->addSql('CREATE INDEX IDX_3535ED9B14C01CC ON hotel (revies_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED9B14C01CC');
        $this->addSql('DROP TABLE revies');
        $this->addSql('DROP INDEX IDX_3535ED9B14C01CC ON hotel');
        $this->addSql('ALTER TABLE hotel DROP revies_id');
        $this->addSql('ALTER TABLE review ADD hotel_id INT NOT NULL');
    }
}
