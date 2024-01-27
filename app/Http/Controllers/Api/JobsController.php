<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ScrapeWebJob;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Http\Requests\JobRequest;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::getAllJobs();

        return response()->json($jobs);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(JobRequest $request)
    {
        $validated = $request->validated();

        $additionalData = [
            'status' => 'CREATED',
            'created_at' =>  date("Y-m-d h:i:s"),
            'updated_at' =>  date("Y-m-d h:i:s"),
            'scraped_data' => null
        ];

        $jobData = array_merge([
            'request_body' => json_encode($validated['request_body'])
        ], $additionalData);

        $job = Job::addJob($jobData);

        // Start add job to queue to scrape data
        ScrapeWebJob::dispatch($job['id'])->delay(5);

        return response()->json(['message' => 'Job created successfully', 'job' => $job], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $jobId)
    {
        $job = Job::getJobById($jobId);

        if ($job) {
            return response()->json($job);
        } else {
            return response()->json(['error' => 'Job not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $jobId)
    {
        $deleted = Job::deleteJob($jobId);

        if ($deleted) {
            return response()->json(['message' => 'Job deleted successfully']);
        } else {
            return response()->json(['error' => 'Job not found'], 404);
        }
    }
}
