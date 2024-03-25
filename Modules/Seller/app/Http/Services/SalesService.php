<?php

namespace Modules\Seller\app\Http\Services;

use App\Core\BaseService;
use Modules\Seller\app\Models\Sales;

class SalesService extends BaseService {

    public function __construct(Sales $sales)
    {
        $this->model = $sales;
    }

   
}