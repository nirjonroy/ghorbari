@extends('Frontend.layouts.master')

@section('title', $page['title'] ?? 'Payment Calculator | Land Site')
@section('body_class', 'calculator-page')

@section('content')
  @php
    $defaults = $calculatorDefaults;
    $calculatorClientDefaults = [
        'homePrice' => $defaults['home_price'],
        'downPercent' => $defaults['down_percent'],
        'loanYears' => $defaults['loan_years'],
        'interestRate' => $defaults['interest_rate'],
        'taxRate' => $defaults['tax_rate'],
        'serviceCharge' => $defaults['service_charge'],
    ];
  @endphp

  <main data-api-url="{{ $page['api_url'] ?? route('api.frontend.calculator') }}">
    <section class="page-hero">
      <div class="container">
        <p>BDT estimate</p>
        <h1>Payment Calculator</h1>
        <p class="calculator-subtitle">Estimate monthly property payments using price, down payment, term, rate, taxes, and service charge.</p>
      </div>
    </section>

    <section class="calculator-section">
      <div class="container">
        <div class="row g-4 align-items-start">
          <div class="col-lg-5">
            <form
              class="calculator-panel"
              id="paymentCalculator"
              data-defaults="{{ json_encode($calculatorClientDefaults) }}"
            >
              <div class="calculator-field">
                <label for="homePrice">Property price</label>
                <div class="input-group">
                  <span class="input-group-text">BDT</span>
                  <input type="number" class="form-control calc-input" id="homePrice" value="{{ $defaults['home_price'] }}" min="{{ $defaults['home_price_min'] }}" step="{{ $defaults['home_price_step'] }}">
                </div>
                <input type="range" class="form-range calc-input" id="homePriceRange" min="{{ $defaults['home_price_min'] }}" max="{{ $defaults['home_price_max'] }}" step="{{ $defaults['home_price_step'] }}" value="{{ $defaults['home_price'] }}">
              </div>

              <div class="calculator-field">
                <label for="downPayment">Down payment</label>
                <div class="input-group">
                  <span class="input-group-text">BDT</span>
                  <input type="number" class="form-control calc-input" id="downPayment" value="{{ $defaults['down_payment'] }}" min="0" step="100000">
                  <span class="input-group-text" id="downPercent">{{ $defaults['down_percent'] }}%</span>
                </div>
                <input type="range" class="form-range calc-input" id="downPaymentRange" min="{{ $defaults['down_percent_min'] }}" max="{{ $defaults['down_percent_max'] }}" step="1" value="{{ $defaults['down_percent'] }}">
              </div>

              <div class="row g-3">
                <div class="col-sm-6 calculator-field">
                  <label for="loanYears">Payment term</label>
                  <select class="form-select calc-input" id="loanYears">
                    @foreach($defaults['loan_year_options'] as $year)
                      <option value="{{ $year }}" @selected($defaults['loan_years'] === $year)>{{ $year }} years</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-6 calculator-field">
                  <label for="interestRate">Rate</label>
                  <div class="input-group">
                    <input type="number" class="form-control calc-input" id="interestRate" value="{{ $defaults['interest_rate'] }}" min="{{ $defaults['interest_min'] }}" max="{{ $defaults['interest_max'] }}" step="0.1">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>

              <div class="row g-3">
                <div class="col-sm-6 calculator-field">
                  <label for="taxRate">Yearly taxes</label>
                  <div class="input-group">
                    <input type="number" class="form-control calc-input" id="taxRate" value="{{ $defaults['tax_rate'] }}" min="{{ $defaults['tax_min'] }}" max="{{ $defaults['tax_max'] }}" step="0.1">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
                <div class="col-sm-6 calculator-field">
                  <label for="serviceCharge">Service charge</label>
                  <div class="input-group">
                    <span class="input-group-text">BDT</span>
                    <input type="number" class="form-control calc-input" id="serviceCharge" value="{{ $defaults['service_charge'] }}" min="{{ $defaults['service_charge_min'] }}" max="{{ $defaults['service_charge_max'] }}" step="{{ $defaults['service_charge_step'] }}">
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="col-lg-7">
            <aside class="calculator-result">
              <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                  <span class="result-label">Estimated payment</span>
                  <h2><span id="monthlyPayment">BDT 0</span> Per Month</h2>
                </div>
                <button class="btn btn-link" id="resetCalculator" type="button">Reset</button>
              </div>
              <div class="result-bar">
                <span id="principalBar"></span>
                <span id="taxBar"></span>
                <span id="serviceBar"></span>
              </div>
              <div class="result-list">
                <div><span><i class="dot principal"></i>Principal and interest</span><strong id="principalValue">BDT 0</strong></div>
                <div><span><i class="dot tax"></i>Property taxes</span><strong id="taxValue">BDT 0</strong></div>
                <div><span><i class="dot service"></i>Service charge</span><strong id="serviceValue">BDT 0</strong></div>
                <div><span><i class="dot total"></i>Payment amount</span><strong id="loanAmount">BDT 0</strong></div>
              </div>
              <a href="{{ route('frontend.agents.index') }}" class="btn btn-danger result-cta">Contact agent</a>
            </aside>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
