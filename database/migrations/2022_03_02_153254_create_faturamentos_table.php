<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaturamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturamentos', function (Blueprint $table) {
            $table->id();
            $table->string('comprador');
            $table->string('descricao');
            $table->integer('preco_unit');
            $table->integer('quantidade');
            $table->string('endereco');
            $table->string('fornecedor');
            $table->foreignId('file_id')->constrained('files');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faturamentos');
    }
}
