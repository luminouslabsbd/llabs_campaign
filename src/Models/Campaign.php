<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Luminouslabs\Installer\Models\SpinnerData;
class Campaign extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function spinnerData()
    {
        return $this->hasMany(SpinnerData::class,'campaign_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'campaign_member')->withPivot(['spinner_round']);
    }

    public function cardMembers()
    {
        return $this->hasMany(MemberCard::class,'campagin_id');
    }
}
