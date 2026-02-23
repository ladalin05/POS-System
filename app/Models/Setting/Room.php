<?php

namespace App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting\Floor;
class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = ['code', 'name','floor_id'];


    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}
