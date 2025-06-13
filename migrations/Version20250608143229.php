<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608143229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula ADD centro_id INT DEFAULT NULL, ADD titulacion_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885298137A7 FOREIGN KEY (centro_id) REFERENCES centro (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885F471CF55 FOREIGN KEY (titulacion_id) REFERENCES matricula (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_15DF1885298137A7 ON matricula (centro_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_15DF1885F471CF55 ON matricula (titulacion_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885298137A7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885F471CF55
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_15DF1885298137A7 ON matricula
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_15DF1885F471CF55 ON matricula
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula DROP centro_id, DROP titulacion_id
        SQL);
    }
}
