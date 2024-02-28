<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Luminouslabs\Installer\Models\SpinReward;
use Luminouslabs\Installer\Models\Member;

class SpinMember extends Model
{
    use HasFactory;
    protected $table ='spiner_members';
    protected $guarded =['id'];

    public function spinMemberReword()
    {
        return $this->hasOne(SpinReward::class,'spiner_member_id');
    }

    public function spinMember()
    {
        return $this->belongsTo(Member::class,'member_id');
    }
}
