<?php

namespace Luminouslabs\Installer\Models;

use App\Models\Partner as BasePartner;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Member
 *
 * Represents a Member in the application.
 *
 * Also it's a wrapper for root Member model
 */
class Partner extends BasePartner
{

    public function Campaigns()
    {
        return $this->hasMany(Campaign::class,'tenant_id');
    }
    public function PartnerCampagins()
    {
        return $this->hasMany(MemberCard::class);
    }
}
