<?php

namespace Luminouslabs\Installer\Models;

use App\Traits\HasCustomShortflakePrimary;
use App\Traits\HasIdentifier;
use App\Traits\HasSchemaAccessors;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Member
 *
 * Represents a Member in the application.
 */
class Member extends Authenticatable implements HasLocalePreference, HasMedia
{
    use HasApiTokens, Notifiable, InteractsWithMedia, HasIdentifier, HasCustomShortflakePrimary, HasSchemaAccessors;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'members';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'meta' => 'array',
        'created_at' => 'datetime',
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should not be exposed by API and other public responses.
     *
     * @var array
     */
    protected $hiddenForPublic = [
        'affiliate_id',
        'role',
        'member_number',
        'display_name',
        'birthday',
        'gender',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'account_expires_at',
        'premium_expires_at',
        'country_code',
        'phone_prefix',
        'phone_country',
        'phone',
        'phone_e164',
        'is_vip',
        'is_active',
        'accepts_text_messages',
        'is_undeletable',
        'is_uneditable',
        'number_of_emails_received',
        'number_of_text_messages_received',
        'number_of_reviews_written',
        'number_of_ratings_given',
        'meta',
        'media',
        'deleted_at',
        'deleted_by',
        'created_by',
        'updated_by',
    ];

    public function hideForPublic()
    {
        $this->makeHidden($this->hiddenForPublic);

        return $this;
    }

    /**
     * Allow mass assignment of a model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Append programmatically added columns.
     *
     * @var array
     */
    protected $appends = [
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_member')->withPivot(['spinner_round']);
    }

}
