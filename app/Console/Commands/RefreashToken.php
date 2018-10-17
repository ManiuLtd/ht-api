<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;

class RefreashToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refreash-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷新京东授权token';

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
     * @return mixed
     */
    public function handle()
    {
        $params = [
            'client_id' => '57116DD1E5EDBA11B73A251A0BEB739E',
            'client_secret' => '8d05a49c2bad4c1fa62caa78c2647757',
            'grant_type' => 'refresh_token',
            'refresh_token' => config('coupon.jingdong.refresh_token'),
        ];

        $rest = Curl::to('https://oauth.jd.com/oauth/token')
            ->withData($params)
            ->get();

        return ;
    }
}
