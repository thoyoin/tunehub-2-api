<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use App\Application\Command\Playlist\CreatePlaylistCommand;
use App\Domain\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CreateStarterPlaylistSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {}

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $this->messageBus->dispatch(new CreatePlaylistCommand(
            (int)$event->getUser()->getId(),
            'Liked Tracks',
        ));
    }
}
