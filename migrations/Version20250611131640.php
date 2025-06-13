<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611131640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE tareas (id INT AUTO_INCREMENT NOT NULL, matricula_id INT DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, fecha_creacion DATE NOT NULL, completada TINYINT(1) NOT NULL, INDEX IDX_BFE3AB3515C84B52 (matricula_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tareas ADD CONSTRAINT FK_BFE3AB3515C84B52 FOREIGN KEY (matricula_id) REFERENCES matricula (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tareas DROP FOREIGN KEY FK_BFE3AB3515C84B52
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tareas
        SQL);
    }
}
