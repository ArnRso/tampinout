<?php

namespace App\Service;

use App\Entity\TamponEvent;
use App\Entity\User;
use App\Enum\TamponAction;
use App\Repository\TamponEventRepository;
use Doctrine\ORM\EntityManagerInterface;

class TamponService
{
    public function __construct(
        private TamponEventRepository $tamponEventRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Récupère le dernier événement tampon pour un utilisateur
     */
    public function getLastEvent(User $user): ?TamponEvent
    {
        return $this->tamponEventRepository->findOneBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * Vérifie si l'utilisateur a actuellement un tampon inséré
     */
    public function hasActiveTampon(User $user): bool
    {
        $lastEvent = $this->getLastEvent($user);

        return $lastEvent && $lastEvent->getAction() === TamponAction::INSERT;
    }

    /**
     * Inverse le statut du tampon (insert <-> remove)
     */
    public function toggleTamponStatus(User $user): TamponEvent
    {
        $hasActiveTampon = $this->hasActiveTampon($user);

        $event = new TamponEvent();
        $event->setUser($user);
        $event->setAction($hasActiveTampon ? TamponAction::REMOVE : TamponAction::INSERT);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }

    /**
     * Récupère l'historique complet des événements pour un utilisateur
     *
     * @return TamponEvent[]
     */
    public function getHistory(User $user): array
    {
        return $this->tamponEventRepository->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );
    }
}
