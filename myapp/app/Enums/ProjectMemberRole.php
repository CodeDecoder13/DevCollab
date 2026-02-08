<?php

namespace App\Enums;

enum ProjectMemberRole: string
{
    case Owner = 'owner';
    case Manager = 'manager';
    case Member = 'member';
}
