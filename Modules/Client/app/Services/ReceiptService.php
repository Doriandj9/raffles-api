<?php

namespace Modules\Client\app\Services;

use App\Core\BaseService;
use Modules\Client\app\Models\Receipt;

class ReceiptService extends BaseService {

    public function __construct(Receipt $receipt)
    {
        $this->model = $receipt;
    }

   
}