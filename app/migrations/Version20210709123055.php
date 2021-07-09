<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210709123055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alter User:login, User:password';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE login login VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX unq_login ON user (login)');
        $this->addSql('CREATE UNIQUE INDEX unq_email ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX unq_login ON user');
        $this->addSql('DROP INDEX unq_email ON user');
        $this->addSql('ALTER TABLE user CHANGE login login VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
