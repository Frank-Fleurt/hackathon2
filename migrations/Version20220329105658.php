<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220329105658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD client_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2519EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_527EDB2519EB6921 ON task (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2519EB6921');
        $this->addSql('DROP INDEX IDX_527EDB2519EB6921 ON task');
        $this->addSql('ALTER TABLE task DROP client_id');
    }
}
