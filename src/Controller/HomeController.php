<?php

namespace App\Controller;

use App\Service\TamponService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(
        private TamponService $tamponService
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $hasActiveTampon = null;

        if ($this->getUser()) {
            $hasActiveTampon = $this->tamponService->hasActiveTampon($this->getUser());
        }

        return $this->render('home/index.html.twig', [
            'hasActiveTampon' => $hasActiveTampon,
        ]);
    }
}
