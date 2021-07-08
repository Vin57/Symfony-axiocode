<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210708141450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Opinion';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT DEFAULT NULL, rating INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE opinion');
    }
}
