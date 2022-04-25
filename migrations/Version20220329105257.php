<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220329105257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, img_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, age DATETIME NOT NULL, mail VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C82E74C06A9F55 (img_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_527EDB25C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74C06A9F55 FOREIGN KEY (img_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E74C06A9F55');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25C54C8C93');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
