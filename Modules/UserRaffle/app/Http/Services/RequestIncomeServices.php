<?php

namespace Modules\UserRaffle\app\Http\Services;

use App\Core\BaseService;
use Modules\UserRaffle\app\Models\RequestIncome;

class RequestIncomeServices extends BaseService {

    public function __construct(RequestIncome $requestIncome)
    {
        $this->model = $requestIncome;
    }
   
}