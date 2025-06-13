<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250605192105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE matricula (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, fecha_incio DATETIME NOT NULL, fecha_fin DATETIME NOT NULL, INDEX IDX_15DF1885A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula ADD CONSTRAINT FK_15DF1885A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD activo TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE matricula DROP FOREIGN KEY FK_15DF1885A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE matricula
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP activo
        SQL);
    }
}
