<?php
namespace App\Helper;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationHelper
{
    public static function dropColumn(string $tableName, array $columns)
    {
        foreach($columns as $column){
            if (Schema::hasColumn($tableName, $column)) {
                Schema::table($tableName, function (Blueprint $table) use ($column){
                    $table->dropColumn($column);
                });
            }
        }
    }
}