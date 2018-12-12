<?php

namespace App\Jobs\Spider;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TalentInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 1;
    /**
     * @var
     */
    protected $data;
    /**
     * @var
     */
    protected $type;

    /**
     * 达人说
     * TalentInfo constructor.
     * @param $data
     * @param $type
     */
    public function __construct($data,$type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * 大人说
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        foreach ($this->data as $value){

            $data['article_id'] = $value->id;
            $data['name'] = $value->name;
            $data['short_title'] = $value->shorttitle;
            $data['thumb'] = $value->app_image;
            $data['label'] = $value->label;
            $data['item_id'] = $value->itemid_str;
            $data['banner'] = $value->article_banner;

            $data['readtimes'] = $value->readtimes;
            $data['followtimes'] = $value->followtimes;
            $data['nickname'] = $value->talent_name;
            $data['headimgurl'] = $value->head_img;
            $data['user_id'] = 1;
            $data['compose_image'] = $value->compose_image;
            $data['content'] = $value->article;
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();

            switch ($this->type){
                case 1:
                    $data['is_top'] = 1;
                    break;
                case 2:
                    $data['is_new'] = 1;
                    break;
                default:
                    $data['is_all'] = 1;
                    break;
            }

            db('tbk_says')->updateOrInsert([
                'article_id'=>$data['article_id'],
                ], $data);
            $data = [];
        }

    }
}
