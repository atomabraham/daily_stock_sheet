<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003102407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deliveries (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, date DATE NOT NULL, INDEX IDX_6F0785684584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, prix_v INT NOT NULL, stock_i INT NOT NULL, stock_livrer INT NOT NULL, stock_total INT NOT NULL, stock_final INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_E54BC0054584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, products_id INT NOT NULL, quantity INT NOT NULL, date DATE NOT NULL, INDEX IDX_6B8170444584665A (product_id), INDEX IDX_6B8170446C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_record (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, stock JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deliveries ADD CONSTRAINT FK_6F0785684584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0054584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B8170444584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B8170446C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deliveries DROP FOREIGN KEY FK_6F0785684584665A');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC0054584665A');
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B8170444584665A');
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B8170446C8A81A9');
        $this->addSql('DROP TABLE deliveries');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE sales');
        $this->addSql('DROP TABLE stock_record');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
