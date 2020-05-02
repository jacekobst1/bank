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
            $table->unsignedBigInteger('source_bill_id');
            $table->unsignedBigInteger('target_bill_id')->nullable();
            $table->char('target_bill_number', 26)->nullable();
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
