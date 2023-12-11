<?php

namespace Luminouslabs\Installer\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Luminouslabs\Installer\Events\EnvironmentSetup;
use Luminouslabs\Installer\Http\Helpers\DatabaseManager;
use Illuminate\Support\Facades\URL;

class InstallController extends Controller
{
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        
    }
    

    
}