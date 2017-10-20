<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    

    public function scopeGetNextSeq($query,$seq_name = ''){
        if(empty($seq_name)){
            $seq_name = 'seq_' . $this->table;
        }
        if('seq_' === $seq_name){
            exit('seq_name is null');
        }
        $sql = "select ".$seq_name.".nextval id from sys.dual";
        $result = DB::select($sql);
        $first = array_shift($result);
        return $first->id ?: 0;
    }


    //
    /**
     * Get the connection of the entity.
     *
     * @return string|null
     */
    public function getQueueableConnection()
    {
        // TODO: Implement getQueueableConnection() method.
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        // TODO: Implement resolveRouteBinding() method.
    }
}
