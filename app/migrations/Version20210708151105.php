<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210708151105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Add 'is_main' to Picture";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE picture ADD is_main TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE picture DROP is_main');
    }
}
