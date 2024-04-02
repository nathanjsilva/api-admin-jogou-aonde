<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentStatusTable extends Migration
{
    public function up()
    {
        Schema::create('payment_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('payment_status')->insert([
            ['name' => 'Aguardando Pagamento'],
            ['name' => 'Pagamento Realizado'],
            ['name' => 'Pagamento Recusado'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_status');
    }
}
