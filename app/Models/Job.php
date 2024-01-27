<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Job extends Model
{
    use HasFactory;

    public static function addJob($data)
    {
        $jobId = Redis::incr('job:id');

        $key = "job:$jobId";

        $data['id'] = $jobId;
        Redis::hmset($key, $data);

        return Job::getJobById($jobId);
    }

    public static function getJobById($jobId, $toJsonArray = true)
    {
        $key = "job:$jobId";

        if ($data = Redis::hgetall($key)) {
            return $toJsonArray ? Job::toArrayJson($data) : $data;
        }

        return null;
    }

    public static function deleteJob($jobId)
    {
        $key = "job:$jobId";

        if (Redis::exists($key)) {
            Redis::del($key);
            return true;
        }

        return false;
    }

    public static function getAllJobs()
    {
//        Redis::flushDB();
        $pattern = 'job:[0-9]*';
        $jobKeys = Redis::keys($pattern);

        $jobs = [];
        foreach ($jobKeys as $key) {
            $jobId = str_replace('laravel_database_job:', '', $key);
            $searchKey = "job:$jobId";
            $jobData = Redis::hgetall($searchKey);
            $jobs[] = Job::toArrayJson($jobData);
        }

        return $jobs;
    }

    public static function updateJob($jobId, $job)
    {
        $key = "job:$jobId";

        if (Redis::hgetall($key)) {
            Redis::hmset($key, $job);
        }

        return null;
    }

    public static function toArrayJson($job): array
    {
        return [
            'id' => $job['id'],
            'status' => $job['status'],
            'request_body' => json_decode($job['request_body'], true),
            'scraped_data' => json_decode($job['scraped_data'], true),
            'created_at' => $job['created_at'],
            'updated_at' => $job['updated_at'],
        ];
    }
}
