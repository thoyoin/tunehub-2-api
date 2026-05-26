<?php

declare(strict_types=1);

namespace App\Infrastructure\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        public string $username,

        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 255)]
        #[Assert\Email]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min: 8, max: 255)]
        #[Assert\NotCompromisedPassword]
        #[Assert\Regex(
            pattern: '/[A-Z]/',
            message: 'Password must contain at least one uppercase letter.'
        )]
        #[Assert\Regex(
            pattern: '/[a-z]/',
            message: 'Password must contain at least one lowercase letter.'
        )]
        #[Assert\Regex(
            pattern: '/\d/',
            message: 'Password must contain at least one number.'
        )]
        #[Assert\Regex(
            pattern: '/[\W_]/',
            message: 'Password must contain at least one symbol.'
        )]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\EqualTo(
            propertyPath: 'password',
            message: 'Password do not match.'
        )]
        public string $passwordConfirmation
    )
    {}
}
