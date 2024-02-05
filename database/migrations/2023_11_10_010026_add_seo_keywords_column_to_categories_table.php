<?php

declare(strict_types=1);

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
        Schema::whenTableDoesntHaveColumn($this->getTableName('categories'), 'seo_keywords', function (Blueprint $table) {
            $table->string('seo_keywords', 260)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::whenTableHasColumn($this->getTableName('categories'),'seo_keywords', function (Blueprint $table) {
            $table->dropColumn('seo_keywords');
        });
    }
};
