<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function members()
    {
        return $this->belongsToMany(Member::class, 'campaign_member')->withPivot(['spinner_round']);
    }

}
