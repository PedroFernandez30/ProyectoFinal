<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200502094845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_4B91E702F9912F5B');
        $this->addSql('DROP INDEX IDX_4B91E702D2D578CC');
        $this->addSql('DROP INDEX IDX_4B91E702791AECDB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comentario AS SELECT id, id_video_id, canal_comentado_id, canal_que_comenta_id, contenido, fecha_comentario FROM comentario');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('CREATE TABLE comentario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_video_id INTEGER DEFAULT NULL, canal_comentado_id INTEGER DEFAULT NULL, canal_que_comenta_id INTEGER NOT NULL, contenido VARCHAR(255) NOT NULL COLLATE BINARY, fecha_comentario DATE NOT NULL, CONSTRAINT FK_4B91E702791AECDB FOREIGN KEY (id_video_id) REFERENCES video (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4B91E702D2D578CC FOREIGN KEY (canal_comentado_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4B91E702F9912F5B FOREIGN KEY (canal_que_comenta_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comentario (id, id_video_id, canal_comentado_id, canal_que_comenta_id, contenido, fecha_comentario) SELECT id, id_video_id, canal_comentado_id, canal_que_comenta_id, contenido, fecha_comentario FROM __temp__comentario');
        $this->addSql('DROP TABLE __temp__comentario');
        $this->addSql('CREATE INDEX IDX_4B91E702F9912F5B ON comentario (canal_que_comenta_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702D2D578CC ON comentario (canal_comentado_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702791AECDB ON comentario (id_video_id)');
        $this->addSql('DROP INDEX IDX_497FA0AC27494');
        $this->addSql('DROP INDEX IDX_497FA0CD9D2EB6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__suscripcion AS SELECT id, canal_al_que_suscribe_id, canal_que_suscribe_id FROM suscripcion');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('CREATE TABLE suscripcion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, canal_al_que_suscribe_id INTEGER NOT NULL, canal_que_suscribe_id INTEGER NOT NULL, CONSTRAINT FK_497FA0CD9D2EB6 FOREIGN KEY (canal_al_que_suscribe_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_497FA0AC27494 FOREIGN KEY (canal_que_suscribe_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO suscripcion (id, canal_al_que_suscribe_id, canal_que_suscribe_id) SELECT id, canal_al_que_suscribe_id, canal_que_suscribe_id FROM __temp__suscripcion');
        $this->addSql('DROP TABLE __temp__suscripcion');
        $this->addSql('CREATE INDEX IDX_497FA0AC27494 ON suscripcion (canal_que_suscribe_id)');
        $this->addSql('CREATE INDEX IDX_497FA0CD9D2EB6 ON suscripcion (canal_al_que_suscribe_id)');
        $this->addSql('DROP INDEX IDX_7CC7DA2C3800B7BB');
        $this->addSql('DROP INDEX IDX_7CC7DA2C10560508');
        $this->addSql('CREATE TEMPORARY TABLE __temp__video AS SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM video');
        $this->addSql('DROP TABLE video');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_canal_id INTEGER NOT NULL, id_categoria_id INTEGER NOT NULL, titulo VARCHAR(100) NOT NULL COLLATE BINARY, descripcion VARCHAR(255) DEFAULT NULL COLLATE BINARY, mg INTEGER NOT NULL, dislike INTEGER NOT NULL, duracion VARCHAR(15) NOT NULL COLLATE BINARY, fecha_publicacion VARCHAR(20) NOT NULL COLLATE BINARY, CONSTRAINT FK_7CC7DA2C3800B7BB FOREIGN KEY (id_canal_id) REFERENCES canal (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7CC7DA2C10560508 FOREIGN KEY (id_categoria_id) REFERENCES categoria (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO video (id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion) SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM __temp__video');
        $this->addSql('DROP TABLE __temp__video');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C3800B7BB ON video (id_canal_id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C10560508 ON video (id_categoria_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_4B91E702791AECDB');
        $this->addSql('DROP INDEX IDX_4B91E702D2D578CC');
        $this->addSql('DROP INDEX IDX_4B91E702F9912F5B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comentario AS SELECT id, id_video_id, canal_comentado_id, canal_que_comenta_id, contenido, fecha_comentario FROM comentario');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('CREATE TABLE comentario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_video_id INTEGER DEFAULT NULL, canal_comentado_id INTEGER DEFAULT NULL, canal_que_comenta_id INTEGER NOT NULL, contenido VARCHAR(255) NOT NULL, fecha_comentario DATE NOT NULL)');
        $this->addSql('INSERT INTO comentario (id, id_video_id, canal_comentado_id, canal_que_comenta_id, contenido, fecha_comentario) SELECT id, id_video_id, canal_comentado_id, canal_que_comenta_id, contenido, fecha_comentario FROM __temp__comentario');
        $this->addSql('DROP TABLE __temp__comentario');
        $this->addSql('CREATE INDEX IDX_4B91E702791AECDB ON comentario (id_video_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702D2D578CC ON comentario (canal_comentado_id)');
        $this->addSql('CREATE INDEX IDX_4B91E702F9912F5B ON comentario (canal_que_comenta_id)');
        $this->addSql('DROP INDEX IDX_497FA0CD9D2EB6');
        $this->addSql('DROP INDEX IDX_497FA0AC27494');
        $this->addSql('CREATE TEMPORARY TABLE __temp__suscripcion AS SELECT id, canal_al_que_suscribe_id, canal_que_suscribe_id FROM suscripcion');
        $this->addSql('DROP TABLE suscripcion');
        $this->addSql('CREATE TABLE suscripcion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, canal_al_que_suscribe_id INTEGER NOT NULL, canal_que_suscribe_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO suscripcion (id, canal_al_que_suscribe_id, canal_que_suscribe_id) SELECT id, canal_al_que_suscribe_id, canal_que_suscribe_id FROM __temp__suscripcion');
        $this->addSql('DROP TABLE __temp__suscripcion');
        $this->addSql('CREATE INDEX IDX_497FA0CD9D2EB6 ON suscripcion (canal_al_que_suscribe_id)');
        $this->addSql('CREATE INDEX IDX_497FA0AC27494 ON suscripcion (canal_que_suscribe_id)');
        $this->addSql('DROP INDEX IDX_7CC7DA2C3800B7BB');
        $this->addSql('DROP INDEX IDX_7CC7DA2C10560508');
        $this->addSql('CREATE TEMPORARY TABLE __temp__video AS SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM video');
        $this->addSql('DROP TABLE video');
        $this->addSql('CREATE TABLE video (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_canal_id INTEGER NOT NULL, id_categoria_id INTEGER NOT NULL, titulo VARCHAR(100) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, mg INTEGER NOT NULL, dislike INTEGER NOT NULL, duracion VARCHAR(15) NOT NULL, fecha_publicacion VARCHAR(20) NOT NULL)');
        $this->addSql('INSERT INTO video (id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion) SELECT id, id_canal_id, id_categoria_id, titulo, descripcion, mg, dislike, duracion, fecha_publicacion FROM __temp__video');
        $this->addSql('DROP TABLE __temp__video');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C3800B7BB ON video (id_canal_id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C10560508 ON video (id_categoria_id)');
    }
}
