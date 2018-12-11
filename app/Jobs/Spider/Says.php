<?php

namespace App\Jobs\Spider;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Says implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var
     */
    protected $says;

    /**
     * Says constructor.
     * @param $says
     */
    public function __construct($says)
    {
        $this->says = $says;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $topdata = $this->says->topdata;
        $newdata = $this->says->newdata;
        $clickdata = $this->says->clickdata;
        foreach ($clickdata as $vvv){
            $data['articleid'] = $vvv->id;
            $data['name'] = $vvv->name;
            $data['shorttitle'] = $vvv->shorttitle;
            $data['image'] = $vvv->image;
            $data['app_image'] = $vvv->app_image;
            $data['label'] = $vvv->label;
            $data['tk_item_id'] = $vvv->tk_item_id;
            $data['article_banner'] = $vvv->article_banner;
            $data['highquality'] = $vvv->highquality;
            $data['readtimes'] = $vvv->readtimes;
            $data['talent_name'] = $vvv->talent_name;
            $data['compose_image'] = $vvv->compose_image;
            $data['talentcat'] = $vvv->talentcat;
            $data['clickdata'] = 'clickdata';
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            db('tbk_says')->updateOrInsert(['articleid'=>$data['articleid']], $data);
        }
        foreach ($newdata as $vv){
            $data['articleid'] = $vv->id;
            $data['name'] = $vv->name;
            $data['shorttitle'] = $vv->shorttitle;
            $data['image'] = $vv->image;
            $data['app_image'] = $vv->app_image;
            $data['label'] = $vv->label;
            $data['tk_item_id'] = $vv->tk_item_id;
            $data['article_banner'] = $vv->article_banner;
            $data['highquality'] = $vv->highquality;
            $data['readtimes'] = $vv->readtimes;
            $data['talent_name'] = $vv->talent_name;
            $data['compose_image'] = $vv->compose_image;
            $data['talentcat'] = $vv->talentcat;
            $data['newdata'] = 'newdata';
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            db('tbk_says')->updateOrInsert(['articleid'=>$data['articleid']], $data);
        }
        foreach ($topdata as $v){
            $data['articleid'] = $v->id;
            $data['name'] = $v->name;
            $data['shorttitle'] = $v->shorttitle;
            $data['image'] = $v->image;
            $data['app_image'] = $v->app_image;
            $data['label'] = $v->label;
            $data['tk_item_id'] = $v->tk_item_id;
            $data['article_banner'] = $v->article_banner;
            $data['highquality'] = $v->highquality;
            $data['readtimes'] = $v->readtimes;
            $data['talent_name'] = $v->talent_name;
            $data['compose_image'] = $v->compose_image;
            $data['talentcat'] = $v->talentcat;
            $data['topdata'] = 'topdata';
            $data['created_at'] = Carbon::now()->toDateTimeString();
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            db('tbk_says')->updateOrInsert(['articleid'=>$data['articleid']], $data);
        }
    }
}
