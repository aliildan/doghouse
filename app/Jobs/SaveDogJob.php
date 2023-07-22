<?php

namespace App\Jobs;

use App\Models\Dog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveDogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   protected User $user;

    protected array $dogData;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, array $dogData)
    {
        Log::info('SaveDogJob constructor', ['user' => $user, 'dogData' => $dogData]);
        $this->user = $user;
        $this->dogData = $dogData;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(10);  // Simulate a long operation

        $this->user->dogs()->create($this->dogData);
        Log::info('Dog saved!', ['user' => $this->user, 'dogData' => $this->dogData]);
    }
}
