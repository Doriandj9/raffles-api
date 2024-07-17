<?php

namespace Modules\UserRaffle\app\Filters;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReceiptFilter extends FilterBuilder
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
            $this->builder->when($taxid, function (Builder $builder) use ($taxid) {
                $builder->join('users','users.id','=','receipts.user_id')
                ->where('users.taxid',$taxid);
            });
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
                $builder->join('users','users.id','=','receipts.user_id')
                ->where('users.last_name','ilike',"%". $search ."%")
                ->orWhere('users.first_name','ilike',"%". $search ."%");
            });

        }
    }

}
