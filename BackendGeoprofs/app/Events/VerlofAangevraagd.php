<?php

namespace App\Events;

use App\Models\VerlofAanvraag;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerlofAangevraagd
{
    public VerlofAanvraag $verlofAanvraag;
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct(VerlofAanvraag $verlofAanvraag)
    {
        $this->verlofAanvraag = $verlofAanvraag;
    }
}
