<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubModule;
use App\Models\SubModuleProgress;
use Carbon\Carbon;

class UpdateSubModuleProgress extends Command
{
    protected $signature = 'update:submodule-progress';
    protected $description = 'Update completed_at for expired submodule progress (reflection & forum only)';

    public function handle()
    {
        $today = now();
        $this->info("Running scheduler at: {$today}"); // <--- Tambahkan ini

        // Ambil submodule yang:
        // 1) type reflection atau forum
        // 2) punya debate_end_time
        // 3) waktunya sudah expired
        SubModule::whereIn('type', ['reflection', 'forum'])
            ->whereNotNull('debate_end_time')
            ->where('debate_end_time', '<', $today)
            ->chunk(100, function ($subModules) use ($today) {

                foreach ($subModules as $sub) {

                    $updatedCount = SubModuleProgress::where('sub_module_id', $sub->id)
                        ->whereNull('completed_at')
                        ->update([
                            'completed_at' => $sub->debate_end_time
                        ]);

                    if ($updatedCount > 0) {
                        $this->info("Updated {$updatedCount} progress row(s) in SubModule ID {$sub->id}");
                    }
                }
            });

        return Command::SUCCESS;
    }

}
