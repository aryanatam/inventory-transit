<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerNonStockItemsNsc extends Migration
{
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER non_stock_code_items_insert BEFORE INSERT ON non_stock_code_items FOR EACH ROW
            BEGIN
              INSERT INTO non_stock_code_items_seq VALUES (NULL);
              SET NEW.nsc = CONCAT("NSC", LPAD(LAST_INSERT_ID(), 3, "0"));
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER non_stock_code_items_insert');
    }
}