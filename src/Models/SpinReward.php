<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinReward extends Model
{
    use HasFactory;
    protected $table ='spiner_rewards';
    protected $guarded =['id'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function memberPass()
    {
        return $this->belongsTo(SpinPoint::class,'member_id');
    }
}
