<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210708152318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Alter Picture:is_main \n Add unique index on Picture";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE picture CHANGE is_main is_main TINYINT(1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX unq_mainpicture_product ON picture (product_id, is_main)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX unq_mainpicture_product ON picture');
        $this->addSql('ALTER TABLE picture CHANGE is_main is_main TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
