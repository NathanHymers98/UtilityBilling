<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240618190037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE house (id INT AUTO_INCREMENT NOT NULL, smart_meter_id INT NOT NULL, postcode VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_67D5399D42AD209E (smart_meter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meter_reading (id INT AUTO_INCREMENT NOT NULL, smart_meter_id INT NOT NULL, meter_id INT NOT NULL, timestamp DATETIME NOT NULL, meter_reading INT NOT NULL, INDEX IDX_814A20F942AD209E (smart_meter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smart_meter (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meter_reading ADD CONSTRAINT FK_814A20F942AD209E FOREIGN KEY (smart_meter_id) REFERENCES smart_meter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399D42AD209E');
        $this->addSql('ALTER TABLE meter_reading DROP FOREIGN KEY FK_814A20F942AD209E');
        $this->addSql('DROP TABLE house');
        $this->addSql('DROP TABLE meter_reading');
        $this->addSql('DROP TABLE smart_meter');
    }
}
