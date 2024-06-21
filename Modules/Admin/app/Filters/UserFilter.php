<?php

namespace Modules\Admin\app\Filters;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserFilter extends FilterBuilder
{

    public function lastName($last_name = null)
    {
        
        if ($last_name) {
            $this->whereClause('last_name',"%{$last_name}%",'like');
        }
    }

    public function taxid($taxid = null)
    {
        if ($taxid) {
            $this->whereInClause('taxid', array_values(explode(',', $taxid)));
        }
    }

    public function search($search = null)
    {
        if($search){
            // $this->groupSearch($search, ['record']);
            $this->builder->when($search, function (Builder $builder) use ($search) {
                $builder->whereRaw(DB::raw('organization_id IN (SELECT id FROM oae_organizations WHERE name LIKE ? OR business_name LIKE ?)'), ["%$search%", "%$search%"])
                ->orWhere('record', "like", "%".$search."%");
                
            });
        }
    }

}
