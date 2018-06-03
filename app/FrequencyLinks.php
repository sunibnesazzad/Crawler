<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FrequencyTable;

class FrequencyLinks extends Model
{
    //
    public function FrequencyTable()
    {
        return $this->hasMany(FrequencyTable::class);
    }
}
