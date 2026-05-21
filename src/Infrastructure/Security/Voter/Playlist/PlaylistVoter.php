<?php

namespace App\Infrastructure\Security\Voter\Playlist;

use App\Domain\Entity\Playlist;
use App\Domain\ValueObject\PlaylistVisibility;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class PlaylistVoter extends Voter
{
    public const string EDIT = 'PLAYLIST_EDIT';
    public const string VIEW = 'PLAYLIST_VIEW';
    public const string DESTROY = 'PLAYLIST_DESTROY';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DESTROY])
            && $subject instanceof Playlist;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        if (!$subject instanceof Playlist) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            self::DESTROY => $this->canDestroy($subject, $user),
            default => false,
        };
    }

    public function canDestroy(Playlist $playlist, mixed $user): bool
    {
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($playlist->getOwner()->getId() !== $user->getId()) {
            return false;
        }

        return true;
    }

    public function canEdit(Playlist $playlist, mixed $user): bool
    {
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($playlist->getOwner()->getId() === $user->getId()) {
            return true;
        }

        return false;
    }

    private function canView(Playlist $playlist, mixed $user): bool
    {
        if ($playlist->getVisibility() === PlaylistVisibility::Public) {
            return true;
        }

        if (!$user instanceof UserInterface) {
            return false;
        }

        return $playlist->getOwner()->getId() === $user->getId();
    }
}
