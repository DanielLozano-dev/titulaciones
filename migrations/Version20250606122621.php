<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606122621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE titulacion_centro (titulacion_id INT NOT NULL, centro_id INT NOT NULL, INDEX IDX_1F6EA294F471CF55 (titulacion_id), INDEX IDX_1F6EA294298137A7 (centro_id), PRIMARY KEY(titulacion_id, centro_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE titulacion_centro ADD CONSTRAINT FK_1F6EA294F471CF55 FOREIGN KEY (titulacion_id) REFERENCES titulacion (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE titulacion_centro ADD CONSTRAINT FK_1F6EA294298137A7 FOREIGN KEY (centro_id) REFERENCES centro (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE titulacion_centro DROP FOREIGN KEY FK_1F6EA294F471CF55
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE titulacion_centro DROP FOREIGN KEY FK_1F6EA294298137A7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE titulacion_centro
        SQL);
    }
}
