<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121113220954 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");
        
        $this->addSql("CREATE SEQUENCE ismemberof_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE fos_user_ismemberof (user_id INT NOT NULL, ismemberof_id INT NOT NULL, PRIMARY KEY(user_id, ismemberof_id))");
        $this->addSql("CREATE INDEX IDX_E352958DA76ED395 ON fos_user_ismemberof (user_id)");
        $this->addSql("CREATE INDEX IDX_E352958D45E18BE7 ON fos_user_ismemberof (ismemberof_id)");
        $this->addSql("CREATE TABLE ismemberof (id INT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_DF975E575E237E06 ON ismemberof (name)");
        $this->addSql("ALTER TABLE fos_user_ismemberof ADD CONSTRAINT FK_E352958DA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE fos_user_ismemberof ADD CONSTRAINT FK_E352958D45E18BE7 FOREIGN KEY (ismemberof_id) REFERENCES ismemberof (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");
        
        $this->addSql("ALTER TABLE fos_user_ismemberof DROP CONSTRAINT FK_E352958D45E18BE7");
        $this->addSql("DROP SEQUENCE ismemberof_id_seq");
        $this->addSql("DROP TABLE fos_user_ismemberof");
        $this->addSql("DROP TABLE ismemberof");
    }
}
