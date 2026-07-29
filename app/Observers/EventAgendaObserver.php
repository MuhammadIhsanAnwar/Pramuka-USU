<?php

namespace App\Observers;

use App\Models\EventAgenda;

class EventAgendaObserver
{

    public function saved(EventAgenda $eventAgenda): void
    {
        // QR generation is handled through the Presensi workflow,
        // not automatically when creating or updating the agenda.
    }
}