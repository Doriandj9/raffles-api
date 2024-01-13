<?php

namespace Modules\Admin\app\Services;

use App\Core\BaseService;
use App\Models\User;

class UserService extends BaseService {

    public function __construct(User $user)
    {
        $this->model = $user;
    }

   
}