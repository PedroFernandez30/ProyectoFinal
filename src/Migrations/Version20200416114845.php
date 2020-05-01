<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200416114845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE canal ADD COLUMN foto_perfil VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX IDX_4B91E702F561DEFF');
        $this->addSql('DROP INDEX IDX_4B91E70251F7CA63');
        $this->addSql('DROP INDEX IDX_4B91E702791AECDB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comentario AS SELECT id, id_video_id, id_canal_comentado_id, id_canal_que_comenta_id, contenido, fecha_comentario FROM comentario');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('CREATE TABLE comentario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_video_id INTEGER DEFAULT NULL, id_canal_comentado_id INTEGER DEFAULT NULL, id_canal_que_comenta_id INTEGER NOT NULL, contenido VARCHAR(255) NOT NULL COLLATE BINARY, fecha_comentario DATE NOT NULL, CONSTRAINT FK_4B91E702791AECDB FOREIGN KEY (id_video_id) REFERENCES video (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4B91E70251F7CA63 FOREIGN KEY (id_canal_comentado_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4B91E702F561DEFF FOREIGN KEY (id_canal_que_comenta_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comentario (id, id_video_id, id_canal_comentado_id, id_canal_que_comenta_id, contenido, fecha_comentario) SELECT id, id_video_id, id_canal_comentado_id, id_canal_que_comenta_id, contenido, fecha_comentario FROM __temp__comentario');
        $this->addSql('DROP TABLE __temp__comentario');
        $this->addSql('CREATE INDEX IDX_4B91E702F561DEFF ON comentario (id_canal_que_comenta_id)');
        $this->addSql('CREATE INDEX IDX_4B91E70251F7CA63 ON comentario (id_canal_comentado_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702791AECDB ON comentario (id_video_id)');
        $this->addSql('DROP INDEX IDX_497FA03800B7BB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__suscripcion AS SELECT id, id_canal_id FROM suscripcion');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('CREATE TABLE suscripcion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_canal_id INTEGER NOT NULL, CONSTRAINT FK_497FA03800B7BB FOREIGN KEY (id_canal_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO suscripcion (id, id_canal_id) SELECT id, id_canal_id FROM __temp__suscripcion');
        $this->addSql('DROP TABLE __temp__suscripcion');
        $this->addSql('CREATE INDEX IDX_497FA03800B7BB ON suscripcion (id_canal_id)');
        $this->addSql('DROP INDEX IDX_7CC7DA2C10560508');
        $this->addSql('DROP INDEX IDX_7CC7DA2C3800B7BB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__video AS SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM video');
        $this->addSql('DROP TABLE video');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_canal_id INTEGER NOT NULL, id_categoria_id INTEGER NOT NULL, titulo VARCHAR(100) NOT NULL COLLATE BINARY, descripcion VARCHAR(255) DEFAULT NULL COLLATE BINARY, mg INTEGER NOT NULL, dislike INTEGER NOT NULL, duracion TIME NOT NULL, fecha_publicacion DATE NOT NULL, CONSTRAINT FK_7CC7DA2C3800B7BB FOREIGN KEY (id_canal_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7CC7DA2C10560508 FOREIGN KEY (id_categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO video (id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion) SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM __temp__video');
        $this->addSql('DROP TABLE __temp__video');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C10560508 ON video (id_categoria_id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C3800B7BB ON video (id_canal_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_E181FB59E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__canal AS SELECT id, email, roles, password, nombre, apellidos, sexo, fnac FROM canal');
        $this->addSql('DROP TABLE canal');
        $this->addSql('CREATE TABLE canal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nombre VARCHAR(100) NOT NULL, apellidos VARCHAR(200) NOT NULL, sexo VARCHAR(2) NOT NULL, fnac DATE NOT NULL)');
        $this->addSql('INSERT INTO canal (id, email, roles, password, nombre, apellidos, sexo, fnac) SELECT id, email, roles, password, nombre, apellidos, sexo, fnac FROM __temp__canal');
        $this->addSql('DROP TABLE __temp__canal');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E181FB59E7927C74 ON canal (email)');
        $this->addSql('DROP INDEX IDX_4B91E702791AECDB');
        $this->addSql('DROP INDEX IDX_4B91E70251F7CA63');
        $this->addSql('DROP INDEX IDX_4B91E702F561DEFF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comentario AS SELECT id, id_video_id, id_canal_comentado_id, id_canal_que_comenta_id, contenido, fecha_comentario FROM comentario');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('CREATE TABLE comentario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_video_id INTEGER DEFAULT NULL, id_canal_comentado_id INTEGER DEFAULT NULL, id_canal_que_comenta_id INTEGER NOT NULL, contenido VARCHAR(255) NOT NULL, fecha_comentario DATE NOT NULL)');
        $this->addSql('INSERT INTO comentario (id, id_video_id, id_canal_comentado_id, id_canal_que_comenta_id, contenido, fecha_comentario) SELECT id, id_video_id, id_canal_comentado_id, id_canal_que_comenta_id, contenido, fecha_comentario FROM __temp__comentario');
        $this->addSql('DROP TABLE __temp__comentario');
        $this->addSql('CREATE INDEX IDX_4B91E702791AECDB ON comentario (id_video_id)');
        $this->addSql('CREATE INDEX IDX_4B91E70251F7CA63 ON comentario (id_canal_comentado_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702F561DEFF ON comentario (id_canal_que_comenta_id)');
        $this->addSql('DROP INDEX IDX_497FA03800B7BB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__suscripcion AS SELECT id, id_canal_id FROM suscripcion');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('CREATE TABLE suscripcion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_canal_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO suscripcion (id, id_canal_id) SELECT id, id_canal_id FROM __temp__suscripcion');
        $this->addSql('DROP TABLE __temp__suscripcion');
        $this->addSql('CREATE INDEX IDX_497FA03800B7BB ON suscripcion (id_canal_id)');
        $this->addSql('DROP INDEX IDX_7CC7DA2C3800B7BB');
        $this->addSql('DROP INDEX IDX_7CC7DA2C10560508');
        $this->addSql('CREATE TEMPORARY TABLE __temp__video AS SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM video');
        $this->addSql('DROP TABLE video');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_canal_id INTEGER NOT NULL, id_categoria_id INTEGER NOT NULL, titulo VARCHAR(100) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, mg INTEGER NOT NULL, dislike INTEGER NOT NULL, duracion TIME NOT NULL, fecha_publicacion DATE NOT NULL)');
        $this->addSql('INSERT INTO video (id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion) SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM __temp__video');
        $this->addSql('DROP TABLE __temp__video');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C3800B7BB ON video (id_canal_id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C10560508 ON video (id_categoria_id)');
    }
}
