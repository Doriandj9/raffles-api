<?php

namespace Modules\UserRaffle\app\Http\Services;

use App\Core\BaseService;
use Modules\UserRaffle\app\Models\BankAccount;

class BankAccountServices extends BaseService {

    public function __construct(BankAccount $bankAccount)
    {
        $this->model = $bankAccount;
    }
   
}