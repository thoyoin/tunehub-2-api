<?php

namespace App\Domain\ValueObject;

enum PlaylistVisibility: string
{
    case Public = 'public';
    case Private = 'private';
}
