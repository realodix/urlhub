<?php

namespace App\Enums;

enum UserType: string
{
    case User = 'user';
    case Guest = 'guest';
    case Bot = 'bot';
}
