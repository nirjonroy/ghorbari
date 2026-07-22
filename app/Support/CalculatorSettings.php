<?php

namespace App\Support;

use App\Models\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CalculatorSettings
{
    public function defaults(Request $request): array
    {
        $settings = $this->settings();
        $homePrice = $this->clampInt((int) $request->query('price', $settings['default_price']), $settings['price_min'], $settings['price_max']);
        $downPercent = $this->clampInt((int) $request->query('down_percent', $settings['default_down_percent']), $settings['down_percent_min'], $settings['down_percent_max']);
        $downPayment = $request->filled('down_payment')
            ? max((int) $request->query('down_payment'), 0)
            : (int) round($homePrice * ($downPercent / 100));
        $downPayment = min($downPayment, $homePrice);
        $loanYears = $this->clampInt((int) $request->query('loan_years', $settings['default_loan_years']), $settings['loan_year_min'], $settings['loan_year_max']);

        return array_merge($settings, [
            'home_price' => $homePrice,
            'home_price_min' => $settings['price_min'],
            'home_price_max' => $settings['price_max'],
            'home_price_step' => $settings['price_step'],
            'down_payment' => $downPayment,
            'down_percent' => $homePrice > 0 ? (int) round(($downPayment / $homePrice) * 100) : $downPercent,
            'loan_years' => $loanYears,
            'loan_year_options' => range($settings['loan_year_min'], $settings['loan_year_max']),
            'interest_rate' => $this->clampFloat((float) $request->query('interest_rate', $settings['default_interest_rate']), $settings['interest_min'], $settings['interest_max']),
            'tax_rate' => $this->clampFloat((float) $request->query('tax_rate', $settings['default_tax_rate']), $settings['tax_min'], $settings['tax_max']),
            'service_charge' => $this->clampInt((int) $request->query('service_charge', $settings['default_service_charge']), $settings['service_charge_min'], $settings['service_charge_max']),
        ]);
    }

    public function estimate(float $price): array
    {
        $settings = $this->settings();
        $homePrice = max($price, 0);
        $downPayment = $homePrice * ($settings['default_down_percent'] / 100);
        $paymentAmount = max($homePrice - $downPayment, 0);
        $months = max($settings['default_loan_years'], 1) * 12;
        $monthlyRate = $settings['default_interest_rate'] / 100 / 12;
        $principal = $monthlyRate
            ? $paymentAmount * ($monthlyRate * pow(1 + $monthlyRate, $months)) / (pow(1 + $monthlyRate, $months) - 1)
            : $paymentAmount / $months;
        $taxes = $homePrice * ($settings['default_tax_rate'] / 100) / 12;
        $service = $settings['default_service_charge'];
        $total = $principal + $taxes + $service;

        return [
            'principal' => $principal,
            'taxes' => $taxes,
            'service' => $service,
            'total' => $total,
            'loan_amount' => $paymentAmount,
            'principal_percent' => $total > 0 ? ($principal / $total) * 100 : 0,
            'tax_percent' => $total > 0 ? ($taxes / $total) * 100 : 0,
            'service_percent' => $total > 0 ? ($service / $total) * 100 : 0,
            'settings' => $settings,
        ];
    }

    public function settings(): array
    {
        $fallback = [
            'price_min' => 1000000,
            'price_max' => 200000000,
            'price_step' => 100000,
            'default_price' => 73500000,
            'down_percent_min' => 0,
            'down_percent_max' => 80,
            'default_down_percent' => 20,
            'loan_year_min' => 5,
            'loan_year_max' => 30,
            'default_loan_years' => 20,
            'interest_min' => 1.0,
            'interest_max' => 20.0,
            'default_interest_rate' => 9.5,
            'tax_min' => 0.0,
            'tax_max' => 5.0,
            'default_tax_rate' => 0.6,
            'service_charge_min' => 0,
            'service_charge_max' => 100000,
            'default_service_charge' => 15000,
            'service_charge_step' => 1000,
        ];

        if (! Schema::hasTable('siteinfo') || ! Schema::hasColumn('siteinfo', 'calculator_default_price')) {
            return $fallback;
        }

        $siteInfo = SiteInfo::query()->first();

        if (! $siteInfo) {
            return $fallback;
        }

        return [
            'price_min' => (int) ($siteInfo->calculator_price_min ?: $fallback['price_min']),
            'price_max' => (int) ($siteInfo->calculator_price_max ?: $fallback['price_max']),
            'price_step' => (int) ($siteInfo->calculator_price_step ?: $fallback['price_step']),
            'default_price' => (int) ($siteInfo->calculator_default_price ?: $fallback['default_price']),
            'down_percent_min' => (int) ($siteInfo->calculator_down_percent_min ?? $fallback['down_percent_min']),
            'down_percent_max' => (int) ($siteInfo->calculator_down_percent_max ?: $fallback['down_percent_max']),
            'default_down_percent' => (int) ($siteInfo->calculator_default_down_percent ?: $fallback['default_down_percent']),
            'loan_year_min' => (int) ($siteInfo->calculator_loan_year_min ?: $fallback['loan_year_min']),
            'loan_year_max' => (int) ($siteInfo->calculator_loan_year_max ?: $fallback['loan_year_max']),
            'default_loan_years' => (int) ($siteInfo->calculator_default_loan_years ?: $fallback['default_loan_years']),
            'interest_min' => (float) ($siteInfo->calculator_interest_min ?? $fallback['interest_min']),
            'interest_max' => (float) ($siteInfo->calculator_interest_max ?: $fallback['interest_max']),
            'default_interest_rate' => (float) ($siteInfo->calculator_default_interest_rate ?: $fallback['default_interest_rate']),
            'tax_min' => (float) ($siteInfo->calculator_tax_min ?? $fallback['tax_min']),
            'tax_max' => (float) ($siteInfo->calculator_tax_max ?: $fallback['tax_max']),
            'default_tax_rate' => (float) ($siteInfo->calculator_default_tax_rate ?: $fallback['default_tax_rate']),
            'service_charge_min' => (int) ($siteInfo->calculator_service_charge_min ?? $fallback['service_charge_min']),
            'service_charge_max' => (int) ($siteInfo->calculator_service_charge_max ?: $fallback['service_charge_max']),
            'default_service_charge' => (int) ($siteInfo->calculator_default_service_charge ?: $fallback['default_service_charge']),
            'service_charge_step' => (int) ($siteInfo->calculator_service_charge_step ?: $fallback['service_charge_step']),
        ];
    }

    private function clampInt(int $value, int $min, int $max): int
    {
        return min(max($value, $min), $max);
    }

    private function clampFloat(float $value, float $min, float $max): float
    {
        return min(max($value, $min), $max);
    }
}
