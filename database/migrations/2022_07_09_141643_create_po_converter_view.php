<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement($this->createView());
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS `po_converter`;');
    }

    private function createView()
    {
        return <<<'SQL'
            CREATE OR REPLACE
            ALGORITHM = UNDEFINED VIEW `po_converter` AS
            select
                `p`.`stock_id` AS `ProductStockID`,
                (`p`.`purity_sell_by_unit` <> 1) AS `SoldByCase`,
                (case
                    when ((`p`.`purity_sell_by_unit` = 1)
                    and (`p`.`inner_units` is null)) then `p`.`master_units`
                    when ((`p`.`purity_sell_by_unit` = 1)
                    and (`p`.`inner_units` < 2)) then `p`.`master_units`
                    when ((`p`.`purity_sell_by_unit` = 1)
                    and (`p`.`inner_units` >= 2)) then `p`.`inner_units`
                    when (`p`.`purity_sell_by_unit` <> 1) then 1
                end) AS `CaseMulti`,
                `asd`.`status` AS `Status`
            from
                (`products` `p`
            left join `as400_stock_data` `asd` on
                ((`p`.`id` = `asd`.`product_id`)))
            where
                ((`p`.`state` = 1)
                    and (`p`.`status` = 20))
        SQL;
    }
};
