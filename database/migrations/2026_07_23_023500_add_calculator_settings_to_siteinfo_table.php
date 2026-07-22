<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->unsignedBigInteger('calculator_price_min')->default(1000000)->after('rent_property_icon');
            $table->unsignedBigInteger('calculator_price_max')->default(200000000)->after('calculator_price_min');
            $table->unsignedBigInteger('calculator_price_step')->default(100000)->after('calculator_price_max');
            $table->unsignedBigInteger('calculator_default_price')->default(73500000)->after('calculator_price_step');
            $table->unsignedTinyInteger('calculator_down_percent_min')->default(0)->after('calculator_default_price');
            $table->unsignedTinyInteger('calculator_down_percent_max')->default(80)->after('calculator_down_percent_min');
            $table->unsignedTinyInteger('calculator_default_down_percent')->default(20)->after('calculator_down_percent_max');
            $table->unsignedTinyInteger('calculator_loan_year_min')->default(5)->after('calculator_default_down_percent');
            $table->unsignedTinyInteger('calculator_loan_year_max')->default(30)->after('calculator_loan_year_min');
            $table->unsignedTinyInteger('calculator_default_loan_years')->default(20)->after('calculator_loan_year_max');
            $table->decimal('calculator_interest_min', 5, 2)->default(1)->after('calculator_default_loan_years');
            $table->decimal('calculator_interest_max', 5, 2)->default(20)->after('calculator_interest_min');
            $table->decimal('calculator_default_interest_rate', 5, 2)->default(9.50)->after('calculator_interest_max');
            $table->decimal('calculator_tax_min', 5, 2)->default(0)->after('calculator_default_interest_rate');
            $table->decimal('calculator_tax_max', 5, 2)->default(5)->after('calculator_tax_min');
            $table->decimal('calculator_default_tax_rate', 5, 2)->default(0.60)->after('calculator_tax_max');
            $table->unsignedBigInteger('calculator_service_charge_min')->default(0)->after('calculator_default_tax_rate');
            $table->unsignedBigInteger('calculator_service_charge_max')->default(100000)->after('calculator_service_charge_min');
            $table->unsignedBigInteger('calculator_default_service_charge')->default(15000)->after('calculator_service_charge_max');
            $table->unsignedBigInteger('calculator_service_charge_step')->default(1000)->after('calculator_default_service_charge');
        });
    }

    public function down(): void
    {
        Schema::table('siteinfo', function (Blueprint $table) {
            $table->dropColumn([
                'calculator_price_min',
                'calculator_price_max',
                'calculator_price_step',
                'calculator_default_price',
                'calculator_down_percent_min',
                'calculator_down_percent_max',
                'calculator_default_down_percent',
                'calculator_loan_year_min',
                'calculator_loan_year_max',
                'calculator_default_loan_years',
                'calculator_interest_min',
                'calculator_interest_max',
                'calculator_default_interest_rate',
                'calculator_tax_min',
                'calculator_tax_max',
                'calculator_default_tax_rate',
                'calculator_service_charge_min',
                'calculator_service_charge_max',
                'calculator_default_service_charge',
                'calculator_service_charge_step',
            ]);
        });
    }
};
