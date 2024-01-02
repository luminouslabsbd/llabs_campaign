<?php

namespace Luminouslabs\Installer\Models;

use App\Models\Member as BaseMember;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Member
 *
 * Represents a Member in the application.
 *
 * Also it's a wrapper for root Member model
 */
class Member extends BaseMember
{
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_member')->withPivot(['spinner_round']);
    }

}
