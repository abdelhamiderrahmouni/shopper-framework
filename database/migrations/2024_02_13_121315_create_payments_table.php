<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Traits\Database;

return new class extends Migration
{
    use Database\Migration;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTableName('payments'), function (Blueprint $table) {
            $this->addCommonFields($table, true);

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('payer_id')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('amount');
            $table->string('currency');
            $table->string('payment_method');
            $table->string('status')->index();
            $table->timestamp('payment_date')->nullable();
            $table->text('payment_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getTableName('payments'));
    }
};
