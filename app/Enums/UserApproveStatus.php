<?php
namespace App\Enums;

enum UserApproveStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case FAILED = 'failed';
    case REJECTED = 'rejected';
}
