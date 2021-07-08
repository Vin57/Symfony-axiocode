<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210708144838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return "Opinion 1<->* User \n Opinion 1<->* Product";
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE opinion ADD product_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B0274584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB02B0274584665A ON opinion (product_id)');
        $this->addSql('CREATE INDEX IDX_AB02B027A76ED395 ON opinion (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B0274584665A');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027A76ED395');
        $this->addSql('DROP INDEX IDX_AB02B0274584665A ON opinion');
        $this->addSql('DROP INDEX IDX_AB02B027A76ED395 ON opinion');
        $this->addSql('ALTER TABLE opinion DROP product_id, DROP user_id');
    }
}
