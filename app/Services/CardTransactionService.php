<?php

namespace App\Services;

use App\Core\BaseService;
use App\Models\CardTransaction;
use App\Traits\FileHandler;

class CardTransactionService extends BaseService {
    use FileHandler;
    public function __construct(CardTransaction $cardTransaction)
    {
        $this->model = $cardTransaction;
    }

   
}