<?php

namespace App\Console\Commands;

use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class QuestionStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qs:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Log::info("questions status corn has been started!");
        $now = Carbon::now();
        $questions = Question::all();
        foreach ($questions as $question) {
            $questionSubmissionTime = Carbon::parse($question->created_at);
            //calculating the difference in question submission time and current time
            $totalDuration = $now->diffInHours($questionSubmissionTime);

            if($totalDuration>24 && $question->employee_id!=null)
            {
                //changing the status to be answered
                $question->status=2;
                $question->save();
            }
        }
    }
}
