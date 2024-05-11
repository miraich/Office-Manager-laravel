<?php

namespace App\Enums;
enum Roles: int
{
    case GENERAL_DIRECTOR = 1;
    case DEPARTMENT_HEAD = 2;
    case PROJECT_MANAGER = 3;
    case EMPLOYEE = 4;
}
