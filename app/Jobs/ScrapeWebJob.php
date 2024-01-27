<?php

namespace App\Jobs;

use App\Models\Job;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class ScrapeWebJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected mixed $jobId;

    /**
     * Create a new job instance.
     */
    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $job = Job::getJobById($this->jobId, false);

        try {
            $requestBody = json_decode($job['request_body'], true);
            $client = new Client(HttpClient::create(['timeout' => 60]));

            $result = [];
            $job['status'] = 'RUNNING';
            Job::updateJob($this->jobId, $job);
            foreach($requestBody as $scrapeUrl) {
                $url = $scrapeUrl['url'];
                $selectors = $scrapeUrl['selectors'];
                $scrapedData = [];
                $requestStatus = true;

                try {
                    $crawler = $client->request('GET', $url);


                    foreach ($selectors as $key => $selector) {
                        if($crawler->filter($selector)->count() > 0) {
                            $scrapedData[$key] = $crawler->filter($selector)->text();
                        }
                    }
                } catch (Exception $e) {
                    $requestStatus = false;
                }


                $result[] = [
                    'url' => $url,
                    'scrapedData' => $scrapedData,
                    'requestStatus' => $requestStatus
                ];
            }

            $job['scraped_data'] = json_encode($result);
            $job['updated_at'] =  date("Y-m-d h:i:s");
            $job['status'] = 'DONE';
            Job::updateJob($this->jobId, $job);
        } catch (Exception $e) {
            Log::error("Fail to run job", [$e]);

            $job['status'] = 'FAILED';
            Job::updateJob($this->jobId, $job);
        }
    }
}
