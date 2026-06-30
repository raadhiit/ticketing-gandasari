<?php

namespace App\Enums;

enum TicketStatus: string
{
    case OPEN = 'OPEN';
    case ASSIGNED = 'ASSIGNED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case WAITING_USER = 'WAITING_USER';
    case RESOLVED = 'RESOLVED';
    case CLOSED = 'CLOSED';
    case CANCELLED = 'CANCELLED';
}
