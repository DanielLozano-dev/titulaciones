<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611073821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885F471CF55
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula ADD activa TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885F471CF55 FOREIGN KEY (titulacion_id) REFERENCES titulacion (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885F471CF55
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula DROP activa
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885F471CF55 FOREIGN KEY (titulacion_id) REFERENCES matricula (id)
        SQL);
    }
}
