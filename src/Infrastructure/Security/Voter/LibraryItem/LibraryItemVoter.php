<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Voter\LibraryItem;

use App\Domain\Entity\LibraryItem;
use App\Domain\ValueObject\PlaylistVisibility;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LibraryItemVoter extends Voter
{
    public const string VIEW = 'LIBRARY_ITEM_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW])
            && $subject instanceof LibraryItem;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        if (!$subject instanceof LibraryItem) {
            return false;
        }

        if ($subject->getUser()->getId() === $user->getId()) {
            return true;
        }

        if (
            $subject->getPlaylist() !== null
            && $subject->getPlaylist()->getVisibility() === PlaylistVisibility::Public
        ) {
            return true;
        }

        return false;
    }
}
