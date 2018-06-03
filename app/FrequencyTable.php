<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FrequencyLinks;

class FrequencyTable extends Model
{
    //
    protected $table='frequency_tables';
    public function FrequencyLink()
    {
        return $this->belongsTo(FrequencyLinks::class);
    }
}
