<?php

namespace Luminouslabs\Installer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinPartner extends Model
{
    use HasFactory;

    protected $table ='spiner_partners';
    protected $guarded =['id'];

}
