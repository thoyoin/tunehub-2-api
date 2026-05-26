<?php

namespace App\Infrastructure\Security\Voter\Playlist;

use App\Domain\Entity\Playlist;
use App\Domain\Entity\User;
use App\Domain\ValueObject\PlaylistVisibility;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends Voter<string, Playlist>
 */
final class PlaylistVoter extends Voter
{
    public const string EDIT = 'PLAYLIST_EDIT';
    public const string VIEW = 'PLAYLIST_VIEW';
    public const string DESTROY = 'PLAYLIST_DESTROY';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DESTROY], true)
            && $subject instanceof Playlist;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            self::DESTROY => $this->canDestroy($subject, $user),
            default => false,
        };
    }

    public function canDestroy(Playlist $playlist, mixed $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($playlist->getOwner()->getId() !== $user->getId()) {
            return false;
        }

        return true;
    }

    public function canEdit(Playlist $playlist, mixed $user): bool
    {
        if (!$user instanceof User) {
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

        if (!$user instanceof User) {
            return false;
        }

        return $playlist->getOwner()->getId() === $user->getId();
    }
}
