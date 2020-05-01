<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409124742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE categoria (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(45) NOT NULL)');
        $this->addSql('CREATE TABLE comentario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contenido VARCHAR(255) NOT NULL, fecha_comentario DATE NOT NULL)');
        $this->addSql('CREATE TABLE suscripcion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titulo VARCHAR(100) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, mg INTEGER NOT NULL, dislike INTEGER NOT NULL, duracion TIME NOT NULL, fecha_publicacion DATE NOT NULL)');
        $this->addSql('DROP INDEX UNIQ_E181FB59E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__canal AS SELECT id, email, roles, password FROM canal');
        $this->addSql('DROP TABLE canal');
        $this->addSql('CREATE TABLE canal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, roles CLOB NOT NULL --(DC2Type:json)
        , nombre VARCHAR(100) NOT NULL, apellidos VARCHAR(200) NOT NULL, sexo VARCHAR(2) NOT NULL, fnac DATE NOT NULL)');
        $this->addSql('INSERT INTO canal (id, email, roles, password) SELECT id, email, roles, password FROM __temp__canal');
        $this->addSql('DROP TABLE __temp__canal');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E181FB59E7927C74 ON canal (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP INDEX UNIQ_E181FB59E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__canal AS SELECT id, email, roles, password FROM canal');
        $this->addSql('DROP TABLE canal');
        $this->addSql('CREATE TABLE canal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO canal (id, email, roles, password) SELECT id, email, roles, password FROM __temp__canal');
        $this->addSql('DROP TABLE __temp__canal');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E181FB59E7927C74 ON canal (email)');
    }
}
