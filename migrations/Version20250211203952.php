<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211203952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (
          id bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
          first_name VARCHAR(255) NOT NULL,
          last_name VARCHAR(255) NOT NULL,
          middle_name VARCHAR(255) DEFAULT NULL,
          birthday DATE NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE country (
          id bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
          name VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE director (
          id bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
          first_name VARCHAR(255) NOT NULL,
          last_name VARCHAR(255) NOT NULL,
          middle_name VARCHAR(255) DEFAULT NULL,
          birthday DATE NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE genre (
          id bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
          name VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE TABLE movie (
          id bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
          country_id INT NOT NULL,
          director_id INT NOT NULL,
          source VARCHAR(255) NOT NULL,
          title VARCHAR(255) NOT NULL,
          description TEXT NOT NULL,
          title_original VARCHAR(255) NOT NULL,
          year SMALLINT NOT NULL,
          rating DOUBLE PRECISION DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_1D5EF26FF92F3E70 ON movie (country_id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F899FB366 ON movie (director_id)');
        $this->addSql('CREATE TABLE movie_genre (
          movie_id INT NOT NULL,
          genre_id INT NOT NULL,
          PRIMARY KEY(movie_id, genre_id)
        )');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
        $this->addSql('CREATE TABLE movie_actor (
          movie_id INT NOT NULL,
          actor_id INT NOT NULL,
          PRIMARY KEY(movie_id, actor_id)
        )');
        $this->addSql('CREATE INDEX IDX_3A374C6510DAF24A ON movie_actor (actor_id)');
        $this->addSql('ALTER TABLE
          movie
        ADD
          CONSTRAINT FK_1D5EF26FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE
          movie
        ADD
          CONSTRAINT FK_1D5EF26F899FB366 FOREIGN KEY (director_id) REFERENCES director (id)');
        $this->addSql('ALTER TABLE
          movie_genre
        ADD
          CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE
          movie_genre
        ADD
          CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE
          movie_actor
        ADD
          CONSTRAINT FK_3A374C658F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE
          movie_actor
        ADD
          CONSTRAINT FK_3A374C6510DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE movie DROP CONSTRAINT FK_1D5EF26FF92F3E70');
        $this->addSql('ALTER TABLE movie DROP CONSTRAINT FK_1D5EF26F899FB366');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229648F93B6FC');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229644296D31F');
        $this->addSql('ALTER TABLE movie_actor DROP CONSTRAINT FK_3A374C658F93B6FC');
        $this->addSql('ALTER TABLE movie_actor DROP CONSTRAINT FK_3A374C6510DAF24A');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('DROP TABLE movie_actor');
    }
}
