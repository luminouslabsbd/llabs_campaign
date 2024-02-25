<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Luminouslabs\Installer\Models\Campaign;
use Illuminate\Database\Eloquent\Model;

class SpinnerData extends Model
{
    use HasFactory;

    protected $table ="spiner_data";
    protected $guarded = ['id'];

    public function campagin()
    {
        return $this->belongsTo(Campaign::class,'campaign_id');
    }
}
