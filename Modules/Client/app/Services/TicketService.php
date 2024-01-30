<?php

namespace Modules\Client\app\Services;

use App\Core\BaseService;
use Modules\Client\app\Models\Ticket;

class TicketService extends BaseService {

    public function __construct(Ticket $ticket)
    {
        $this->model = $ticket;
    }

   
}