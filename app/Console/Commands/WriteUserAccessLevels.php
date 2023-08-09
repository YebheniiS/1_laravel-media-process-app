<?php

namespace App\Console\Commands;

use App\Models\AccessLevel;
use App\Models\User;
use Illuminate\Console\Command;

class WriteUserAccessLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:UserAccessLevels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move the user access levels from column ints to a separate pivot table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        // We grab these manually just in case the id's have changed on the live DB
        $interactrId = AccessLevel::where('name', 'interactr')->pluck('id')->first();
        $proId =  AccessLevel::where('name', 'interactr_pro')->pluck('id')->first();
        $agencyId =  AccessLevel::where('name', 'interactr_agency')->pluck('id')->first();
        $adminId =  AccessLevel::where('name', 'admin')->pluck('id')->first();

        foreach($users as $user){
            $user->load('access');

            if(! count($user->access)) {
                // We have no roles yet so attached the interactr tole
                $user->access()->attach($interactrId);
            }

            if($user->superuser){
                $user->access()->attach($adminId);
            }

            if($user->is_pro){
                $user->access()->attach($proId);
            }

            if($user->is_agency_club){
                $user->access()->attach($agencyId);
            }
        }
    }
}
