<?php

namespace App\Shared\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case ASSIGNED = 'assigned';
    case IN_TRANSIT = 'in_transit';
    case DELIVERED = 'delivered';
    case INCIDENT = 'incident';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
