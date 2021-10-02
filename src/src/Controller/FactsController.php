<?php

namespace App\Controller;

use App\UseCase\EvaluateDsqlQueryInterface;
use App\QueryBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FactsController extends AbstractController
{
    #[Route('/facts', name: 'facts')]
    public function index(
        Request $request,
        QueryBuilderInterface $queryBuilder,
        EvaluateDsqlQueryInterface $useCase,
    ): Response
    {
        return $this->json([
            'result' => $useCase->execute(
                $queryBuilder->fromString($request->query->get('q') ?: $request->getContent())
            ),
        ]);
    }
}
