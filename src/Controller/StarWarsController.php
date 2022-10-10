<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StarWarsController extends AbstractController
{
    #[Route(path: '/peopleList', name: 'api')]
    public function api(ApiService $apiService): Response
    {
        return $this->render('base.html.twig', [
            'titlePage' => 'STAR WARS',
            'title'     => 'People list',
            'values'    => $apiService->getDisplayData()
        ]);
    }
}
