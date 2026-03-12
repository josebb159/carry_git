<?php

namespace App\Shared\Enums;

enum EventType: string
{
    case TRUCK_ASSIGNED = 'truck_assigned';
    case ARRIVED_LOADING = 'arrived_loading';
    case LOADED = 'loaded';
    case DEPARTED = 'departed';
    case ARRIVED_UNLOAD = 'arrived_unload';
    case UNLOADED = 'unloaded';
    case INCIDENT = 'incident';
    case REQUEST_CREATED = 'request_created';
}
