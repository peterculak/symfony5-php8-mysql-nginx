<?php

namespace App\Controller;

use App\QueryBuilder;
use App\Repository\FactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FactsController extends AbstractController
{
    #[Route('/facts', name: 'facts')]
    public function index(Request $request, QueryBuilder $queryBuilder, FactRepository $repository): Response
    {
        $query = $queryBuilder->fromString($request->query->get('q') ?: $request->getContent());

        return $this->json([
            'result' => $query->result($repository),
        ]);
    }
}
