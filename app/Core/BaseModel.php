<?php

namespace App\Core;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use AuditBoot;

    public function scopeFilters($query, FilterBuilder $filter)
    {
        return $filter->apply($query);
    }
}