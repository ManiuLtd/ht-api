<?php

use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cms_categories')->truncate();
        DB::table('cms_articles')->truncate();
        DB::table('cms_projects')->truncate();

        factory(App\Models\Cms\Categories::class, 20)->create();
        factory(App\Models\Cms\Article::class, 30)->create();
        factory(App\Models\Cms\Projects::class, 50)->create();

    }
}
