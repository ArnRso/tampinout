<?php

namespace App\Controller;

use App\Service\TamponService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class TamponController extends AbstractController
{
    public function __construct(
        private TamponService $tamponService
    ) {
    }

    #[Route('/suivi', name: 'app_suivi')]
    public function suivi(): Response
    {
        $user = $this->getUser();
        $hasActiveTampon = $this->tamponService->hasActiveTampon($user);
        $lastEvent = $this->tamponService->getLastEvent($user);

        return $this->render('tampon/suivi.html.twig', [
            'hasActiveTampon' => $hasActiveTampon,
            'lastEvent' => $lastEvent,
        ]);
    }

    #[Route('/tampon/toggle', name: 'app_tampon_toggle', methods: ['POST'])]
    public function toggle(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('tampon_toggle', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $user = $this->getUser();

        $event = $this->tamponService->toggleTamponStatus($user);

        $this->addFlash('success', $event->getAction()->value === 'insert'
            ? 'Tampon inséré !'
            : 'Tampon retiré !');

        return $this->redirectToRoute('app_suivi');
    }
}
