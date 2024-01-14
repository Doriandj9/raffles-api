<?php

namespace Modules\Admin\app\Services;

use App\Core\BaseService;
use Modules\Admin\app\Models\AuthorizationRaffle;

class AuthRafflesService extends BaseService {

    public function __construct(AuthorizationRaffle $authorizationRaffle)
    {
        $this->model = $authorizationRaffle;
    }

   
}