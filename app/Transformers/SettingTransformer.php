<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Setting;

/**
 * Class SettingTransformer.
 *
 * @package namespace App\Transformers;
 */
class SettingTransformer extends TransformerAbstract
{
    /**
     * @param \App\Models\System\Setting $model
     * @return array
     */
    public function transform(\App\Models\System\Setting $model)
    {
        return [

            'xieyi' => $model->xieyi,
            'level_desc' => $model->level_desc
        ];
    }
}
