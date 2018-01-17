<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('invoice_no')->nullable();
            $table->string('client')->nullable();
            $table->string('bill_to')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_city')->nullable();
            $table->string('client_country')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('invoice_due_date')->nullable();
            $table->longText('invoice_notes')->nullable();
	        $table->longText('logo')->nullable();

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
        Schema::dropIfExists('invoices');
    }
}
