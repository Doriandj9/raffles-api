<?php

namespace Modules\Admin\app\Services;

use App\Core\BaseService;
use Modules\Admin\app\Models\Winners;

class WinnersService extends BaseService {

    public function __construct(Winners $winners)
    {
        $this->model = $winners;
    }

   
}