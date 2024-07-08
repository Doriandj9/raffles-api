<?php

namespace Modules\Client\app\Services;

use App\Core\BaseService;
use Modules\Client\app\Models\Rating;

class RatingService extends BaseService {

    public function __construct(Rating $rating)
    {
        $this->model = $rating;
    }

   
}