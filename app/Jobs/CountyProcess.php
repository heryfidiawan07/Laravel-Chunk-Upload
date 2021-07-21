<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CountyProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $chunk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $chunk)
    {
        $this->header = $header;
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->chunk as $row) {
            $countries = array_combine($this->header, $row);
            \App\Country::create($countries);
        }
    }
}
