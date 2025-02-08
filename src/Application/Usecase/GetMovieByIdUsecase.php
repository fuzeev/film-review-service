<?php
declare(strict_types = 1);

namespace App\Application\Usecase;

use App\Application\Dto\GetMovieByIdRequest;
use App\Application\Dto\GetMovieByIdResponse;
use App\Domain\Repository\IMovieRepository;

class GetMovieByIdUsecase
{
    public function __construct(
        protected IMovieRepository $movieRepository
    ) {}

    public function execute(GetMovieByIdRequest $request): GetMovieByIdResponse
    {
        $id = $request->movieId;

        $movie = $this->movieRepository->getById($id);

        return new GetMovieByIdResponse($movie);
    }
}