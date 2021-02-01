<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131112341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_balance (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, balance NUMERIC(10, 2) NOT NULL, UNIQUE INDEX UNIQ_FEC95B8319EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, from_client_id INT NOT NULL, to_client_id INT NOT NULL, amount NUMERIC(10, 2) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_6D28840D977FC9B (from_client_id), INDEX IDX_6D28840D2848BE50 (to_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_task (id INT AUTO_INCREMENT NOT NULL, from_client_id INT NOT NULL, to_client_id INT NOT NULL, payment_id INT DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, scheduled_for DATETIME NOT NULL, state SMALLINT NOT NULL, INDEX IDX_73FD4C51977FC9B (from_client_id), INDEX IDX_73FD4C512848BE50 (to_client_id), UNIQUE INDEX UNIQ_73FD4C514C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_balance ADD CONSTRAINT FK_FEC95B8319EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D977FC9B FOREIGN KEY (from_client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D2848BE50 FOREIGN KEY (to_client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_task ADD CONSTRAINT FK_73FD4C51977FC9B FOREIGN KEY (from_client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_task ADD CONSTRAINT FK_73FD4C512848BE50 FOREIGN KEY (to_client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment_task ADD CONSTRAINT FK_73FD4C514C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_balance DROP FOREIGN KEY FK_FEC95B8319EB6921');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D977FC9B');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D2848BE50');
        $this->addSql('ALTER TABLE payment_task DROP FOREIGN KEY FK_73FD4C51977FC9B');
        $this->addSql('ALTER TABLE payment_task DROP FOREIGN KEY FK_73FD4C512848BE50');
        $this->addSql('ALTER TABLE payment_task DROP FOREIGN KEY FK_73FD4C514C3A3BB');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_balance');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_task');
    }
}
