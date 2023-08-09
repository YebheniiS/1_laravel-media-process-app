<?php

namespace App\Console\Commands;

use App\Models\User;
use App\UsagePlans;
use Illuminate\Console\Command;

class UpdateUsagePlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $basicPlanId = UsagePlans::where('name', 'interactr_basic')->first()->id;
            $proPlanId = UsagePlans::where('name', 'interactr_pro')->first()->id;

            $users = User::all();
            $this->line("Processing " . count($users) . " users");

            foreach ($users as $user) {
                if($user->is_pro) {
                    $user->usage_plan_id = $proPlanId;
                } else {
                    $user->usage_plan_id = $basicPlanId;
                }
                $user->save();
            }
            $this->line("Completed");
        } catch (\Exception $exception){
            $this->error("Message: " . $exception->getMessage() . "   Trace: " . $exception->getTraceAsString());
        }
        return 0;
    }
}
