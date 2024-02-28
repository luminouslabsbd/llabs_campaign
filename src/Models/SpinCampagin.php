<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Luminouslabs\Installer\Models\SpinMember;


class SpinCampagin extends Model
{
    use HasFactory;
    protected $table ='spiner_campaigns';
    protected $guarded =['id'];

    public function spinMembers()
    {
        return $this->hasMany(SpinMember::class,'spiner_campaign_id');
    }

    public function rewords()
    {
        return $this->hasMany(SpinReward::class)->where('campaign_id',$this->campaign_id);
    }

}
