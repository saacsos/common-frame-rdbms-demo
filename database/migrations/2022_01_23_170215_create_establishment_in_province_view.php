<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentInProvinceView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ini_set('memory_limit', '-1');
        \Illuminate\Support\Facades\DB::unprepared(file_get_contents(storage_path("sql/establishment_in_provinces.sql")));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
