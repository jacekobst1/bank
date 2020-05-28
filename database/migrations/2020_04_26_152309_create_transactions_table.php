<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type_id');
            $table->unsignedBigInteger('source_bill_id')->nullable();
            $table->unsignedBigInteger('target_bill_id');
            $table->unsignedBigInteger('card_id')->nullable();
            $table->decimal('amount');
            $table->timestamps();

            $table->foreign('source_bill_id')
                ->references('id')
                ->on('bills')
                ->onDelete('cascade');

            $table->foreign('target_bill_id')
                ->references('id')
                ->on('bills')
                ->onDelete('cascade');

            $table->foreign('card_id')
                ->references('id')
                ->on('cards')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
