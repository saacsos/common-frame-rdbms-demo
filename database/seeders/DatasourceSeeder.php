<?php

namespace Database\Seeders;

use App\Models\Datasource;
use Illuminate\Database\Seeder;

class DatasourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datasource = Datasource::first();
        if (!$datasource) {
            $datasource = new Datasource();
            $datasource->name = 'สำนักงานสถิติแห่งชาติ';
            $datasource->save();

            $datasource = new Datasource();
            $datasource->name = 'กรมพัฒนาธุรกิจการค้า';
            $datasource->save();

            $datasource = new Datasource();
            $datasource->name = 'กรมโรงงานอุตสาหกรรม';
            $datasource->save();
        }
    }
}
