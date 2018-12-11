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

            $data['articleid'] = $value->id;
            $data['name'] = $value->name;
            $data['shorttitle'] = $value->shorttitle;
            $data['app_image'] = $value->app_image;
            $data['label'] = $value->label;
            $data['tk_item_id'] = $value->itemid_str;
            $data['article_banner'] = $value->article_banner;
            $data['highquality'] = $value->highquality;
            $data['readtimes'] = $value->readtimes;
            $data['followtimes'] = $value->followtimes;
            $data['talent_name'] = $value->talent_name;
            $data['head_img'] = $value->head_img;
            $data['talent_id'] = $value->talent_id;
            $data['compose_image'] = $value->compose_image;
            $data['content'] = $value->article;
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();

            switch ($this->type){
                case 1:
                    $data['is_topdata'] = 1;
                    break;
                case 2:
                    $data['is_newdata'] = 1;
                    break;
                default:
                    $data['is_clickdata'] = 1;
                    break;
            }

            db('tbk_says')->updateOrInsert([
                'articleid'=>$data['articleid'],
                ], $data);
            $data = [];
        }

    }
}
