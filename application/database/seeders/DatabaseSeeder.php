<?php

namespace Database\Seeders;

use App\Jobs\GetNewsJob;
use App\Services\NewsService;
use App\Services\Parsers\NewsApi\NewsApiParser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        dispatch(new GetNewsJob());
        // $this->call('UsersTableSeeder');

//        $service=new NewsApiParser();
//        dd($service->getNewItemsByQuery('Bitcoin'));
    }
}
