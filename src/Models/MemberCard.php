<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Luminouslabs\Installer\Models\Campaign;
use Illuminate\Database\Eloquent\Model;

class MemberCard extends Model
{
    use HasFactory;

    protected $table ="member_cards";
    protected $guarded = ['id'];

    public function campagin()
    {
        return $this->belongsTo(Campaign::class,'campaign_id');
    }

    public function cardMembers()
    {
        return $this->hasMany(Member::class,'member_id');
    }
}
