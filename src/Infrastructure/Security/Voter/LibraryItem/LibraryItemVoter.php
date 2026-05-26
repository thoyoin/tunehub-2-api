<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Voter\LibraryItem;

use App\Domain\Entity\LibraryItem;
use App\Domain\Entity\User;
use App\Domain\ValueObject\PlaylistVisibility;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, LibraryItem>
 */
class LibraryItemVoter extends Voter
{
    public const string VIEW = 'LIBRARY_ITEM_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW], true)
            && $subject instanceof LibraryItem;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($subject->getUser()->getId() === $user->getId()) {
            return true;
        }

        return false;
    }
}
