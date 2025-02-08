<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\MovieSource;

/**
 * @property int         $id            Идентификатор фильма.
 * @property MovieSource $source        Источник данных фильма.
 * @property string      $title         Заголовок фильма.
 * @property string      $description   Описание фильма.
 * @property string      $nameLocalized Локализованное название.
 * @property string      $nameOriginal  Оригинальное название.
 * @property int         $year          Год выпуска.
 * @property Genre[]     $genres        Массив жанров (объекты класса Genre).
 * @property Director    $director      Режиссёр фильма.
 * @property Actor[]     $actors        Массив актёров (объекты класса Actor).
 * @property float       $rating        Рейтинг фильма.
 */
class Movie
{
    public function __construct(
        public int $id,
        public MovieSource $source,
        public string $title,
        public string $description,
        public string $nameLocalized,
        public string $nameOriginal,
        public int $year,
        public array $genres, //Genres[]
        public Director $director,
        public array $actors, //Actors[]
        public float $rating,
    ) {
    }
}
