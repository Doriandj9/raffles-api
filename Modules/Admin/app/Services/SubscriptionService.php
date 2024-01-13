<?php

namespace Modules\Admin\app\Services;

use App\Core\BaseService;
use Modules\Admin\app\Models\Subscription;

class SubscriptionService extends BaseService {

    public function __construct(Subscription $subscription)
    {
        $this->model = $subscription;
    }

   
}