<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTypesTable extends Migration
{
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('payment_types')->insert([
            ['name' => 'Pix'],
            ['name' => 'Cartão de Crédito'],
            ['name' => 'Boleto Bancário'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_types');
    }
}
