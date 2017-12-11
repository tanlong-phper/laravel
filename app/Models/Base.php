<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    

    /*public function scopeGetNextSeq($query,$seq_name = ''){
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

*/

}
