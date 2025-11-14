<?php

namespace App\Enums;

enum UserRole: string {
    case ADMIN = 'admin';
    case JUDGE = 'judge';
    case STUDENT = 'student';
    case INSTITUTION = 'institution';
    case STAGE_ADMIN = 'stage_admin';
}
