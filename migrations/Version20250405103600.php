<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405103600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Создание таблицы review
        $this->addSql('
            CREATE TABLE review (
                id SERIAL NOT NULL,
                author_id INT NOT NULL,
                movie_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                text VARCHAR(1024) NOT NULL,
                rating INT NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        // Добавление внешнего ключа на таблицу "user"
        $this->addSql('
            ALTER TABLE review
            ADD CONSTRAINT FK_review_author
            FOREIGN KEY (author_id) REFERENCES "user"(id)
            NOT DEFERRABLE INITIALLY IMMEDIATE
        ');

        $this->addSql('
            ALTER TABLE review
            ADD CONSTRAINT FK_review_movie
            FOREIGN KEY (movie_id) REFERENCES "movie"(id)
            NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        // Удаление таблицы review
        $this->addSql('DROP TABLE review');
    }
}
