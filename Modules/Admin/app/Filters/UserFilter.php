<?php

namespace Modules\Admin\app\Filters;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserFilter extends FilterBuilder
{

    public function fullName($full_name = null)
    {
        
        if ($full_name) {
            $this->whereClause('last_name',"%{$full_name}%",'like');          
        }
    }

    public function taxid($taxid = null)
    {
        if ($taxid) {
            $this->whereInClause('taxid', array_values(explode(',', $taxid)));
        }
    }

    public function filters($filters = null){
        if($filters){
            $filters = preg_replace('/&#34;/','"',$filters);
            $data = json_decode($filters);

            if($data->key === 'taxid'){
                $this->taxid($data->value);
            }

            if($data->key === 'search'){
                $this->search($data->value);
            }
        }
    }

    public function search($search = null)
    {
        if($search){
            // $this->groupSearch($search, ['record']);
            $this->builder->when($search, function (Builder $builder) use ($search) {
                $builder->where('last_name','ilike',"%". $search ."%")
                ->orWhere('first_name','ilike',"%". $search ."%");
            });

        }
    }

}
