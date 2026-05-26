<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Voter\Release;

use App\Domain\Entity\Release;
use App\Domain\Entity\User;
use App\Domain\ValueObject\ReleaseStatus;
use App\Domain\ValueObject\UserRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, Release>
 */
final class ReleaseVoter extends Voter
{
    public const string CREATE = 'RELEASE_CREATE';
    public const string UPDATE = 'RELEASE_UPDATE';
    public const string PUBLISH = 'RELEASE_PUBLISH';
    public const string DELETE = 'RELEASE_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::UPDATE, self::PUBLISH, self::DELETE], true);
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
        ?Vote $vote = null
    ): bool {
        $user = $token->getUser();

        return match ($attribute) {
            self::CREATE => $this->canCreate($user),
            self::UPDATE => $this->canUpdate($user, $subject),
            self::PUBLISH => $this->canPublish($user, $subject),
            self::DELETE => $this->canDelete($user, $subject),
            default => false,
        };
    }

    public function canCreate(mixed $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($user->getRole() !== UserRole::Artist) {
            return false;
        }

        return true;
    }

    public function canUpdate(mixed $user, Release $subject): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($user->getRole() !== UserRole::Artist) {
            return false;
        }

        if ($user->getId() !== $subject->getArtist()->getId()) {
            return false;
        }

        return true;
    }

    public function canPublish(mixed $user, Release $subject): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($user->getRole() !== UserRole::Artist) {
            return false;
        }

        if ($user->getId() !== $subject->getArtist()->getId()) {
            return false;
        }

        if ($subject->getStatus() !== ReleaseStatus::APPROVED) {
            return false;
        }

        return true;
    }

    public function canDelete(mixed $user, Release $subject): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($user->getRole() !== UserRole::Artist) {
            return false;
        }

        if ($user->getId() !== $subject->getArtist()->getId()) {
            return false;
        }

        return true;
    }
}
