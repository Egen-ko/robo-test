<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210131170000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('INSERT INTO client (name) values ("Никита Сергеевич"),("Валентин Александрович"),("Августин Семенович"),("Марфа Васильевна");');
        $this->addSql('INSERT INTO client_balance (client_id, balance) SELECT id, 1054 FROM client WHERE name = "Никита Сергеевич";');
        $this->addSql('INSERT INTO client_balance (client_id, balance) SELECT id, 2500 FROM client WHERE name = "Валентин Александрович";');
        $this->addSql('INSERT INTO client_balance (client_id, balance) SELECT id, 1800 FROM client WHERE name = "Августин Семенович";');
        $this->addSql('INSERT INTO client_balance (client_id, balance) SELECT id, 500 FROM client WHERE name = "Марфа Васильевна";');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM client WHERE name IN ("Никита Сергеевич","Валентин Александрович","Августин Семенович","Марфа Васильевна")');
    }
}
