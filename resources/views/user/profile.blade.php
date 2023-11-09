@extends('layouts/app')
@section('title', 'profile')
@section('links-css')
  <link rel="stylesheet" href="{{ asset('assets/projects/assets/css/chatbox.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/projects/assets/css/create.css') }}" />
  <style>
  </style>
@endsection
@section('content')
  <ul class="nav nav-tabs mb-3 mt-5" id="ex1" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link nav-tab active" data-bs-toggle="tab" data-id="#tab-1" href="javascript:void(0)" role="tab"
        aria-controls="ex1-tabs-1" aria-selected="true">
        Profile<i class="ti ti-user-circle ms-1"></i>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link nav-tab" data-bs-toggle="tab" data-id="#tab-2" href="javascript:void(0)" role="tab"
        aria-controls="ex1-tabs-2" aria-selected="false">
        Billing<i class="ti ti-brand-stripe ms-1"></i>
      </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link nav-tab" data-bs-toggle="tab" data-id="#tab-3" href="javascript:void(0)" role="tab"
          aria-controls="ex1-tabs-3" aria-selected="false">
          Payment Method<i class="ti ti-brand-mastercard ms-1"></i>
        </a>
      </li>
  </ul>
  <!-- Tabs navs -->
  <!-- Tabs content -->
  <div class="tab-content" id="ex1-content">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="ex1-tab-1">
      <div class="d-flex flex-sm-row flex-column">
        <form id="line-form" class="line-container disabled" method="post" action="" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="username" class="form-label">User Name</label>
            <input value="{{ !empty($user) ? $user->name : '' }}" type="text" class="form-user form-control"
              id="user-name">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input value="{{ !empty($user) ? $user->email : '' }}" type="text" class="form-email form-control"
              id="email">
          </div>
          <div class="mb-3">
            <label for="provider-icon" class="form-label">Created at</label>
            <input pattern="\d{4}/\d{2}/\d{2}"
              value="{{ !empty($user) ? date('Y-m-d', strtotime($user->created_at)) : '' }}" type="date"
              class="form-user form-control mb-3" id="created_at">
          </div>
        </form>
        <div class="line-container">
          <div class="mb-3">
            <label for="provider-icon" class="form-label">Avatar</label>
            @if (!empty($user) && !empty($user->avatar))
              <div class="position-relative icon-provider qr-code-provider mx-auto ">
                <img src="{{ asset($user->avatar) }}" />
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="ex1-tab-2">
      <div class="card">
        <div class="border-bottom p-3">
          <h4 class="card-title mb-0">Billing</h4>
        </div>
        <div class="card-body">
          <table class="tablesaw table-striped table-hover table-bordered table no-wrap tablesaw-columntoggle"
            data-tablesaw-mode="columntoggle" id="tablesaw-1256">
            <thead>
              <tr>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-priority="persist" class="border">
                  Subscription
                </th>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-sortable-default-col=""
                  data-tablesaw-priority="3" class="border tablesaw-priority-3">
                  Price
                </th>
                <th scope="col" data-tablesaw-sortable-col="" data-tablesaw-priority="2"
                  class="border tablesaw-priority-2">
                  Payment date
                </th>
              </tr>
            </thead>
            <tbody>
              @if (!empty($invoices))
                @foreach ($invoices as $invoice)
                  @if ($invoice->status == 'paid')
                    <tr>
                      <td class="tablesaw-priority-2">{{ $invoice->lines->data[0]->description }}</td>
                      <td class="tablesaw-priority-1">{{ number_format($invoice->amount_paid / 100, 2, '.', '') }}
                        &nbsp;/&nbsp; {{ $invoice->currency }}</td>
                      <td class="tablesaw-priority-4">{{ date('Y-m-d', $invoice->created) }}</td>
                    </tr>
                  @endif
                @endforeach
              @endif
            </tbody>
          </table>
        </div>

      </div>
    </div>
    <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="ex1-tab-3">
        @if (isset($user) && $user->customer && !empty($user->customer->customer_stripe_id))
            @include('user.payment_method')
        @else
          <div class="alert alert-warning disabled text-center">
            <span class="ms-3 text-danger">You don't have payment information to update.</span>
          </div>
        @endif
      </div>
    <div>
    @endsection
    @section('links-script')
      <script>
        $(document).ready(function() {
          const queryString = window.location.search;
          const urlParams = new URLSearchParams(queryString);
          var currentTab = urlParams.get('tab');
          if (currentTab) {
            const elementTab = document.querySelector('[data-id="#' + currentTab + '"]');
            $('.tab-pane').removeClass('show active');
            $('.nav-tab').removeClass('active');
            $('#' + currentTab).addClass('show active');
            $(elementTab).addClass('active');
            elementTab.focus();
          }
          $('.nav-tab').on('click', function() {
            $('.nav-tab').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('show active');
            var tabId = $(this).attr('data-id');
            $(tabId).addClass('show active');
            //add param tab url
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            urlParams.set('tab', tabId.replace("#", ""));
            const modifiedUrl = window.location.pathname + '?' + urlParams.toString();
            window.history.replaceState({}, '', modifiedUrl);
          });

          $("#ex1").keydown(function(e) {
            if (e.keyCode == 9) {
              e.preventDefault();
              const queryString = window.location.search;
              const urlParams = new URLSearchParams(queryString);
              var currentTab = urlParams.get('tab');
              var newTab = 'tab-1';
              if (currentTab) {
                if (currentTab == 'tab-1') {
                  newTab = 'tab-2';
                } else if (currentTab == 'tab-2') {
                  newTab = 'tab-3';
                } else if (currentTab == 'tab-3') {
                  newTab = 'tab-1';
                }
              } else {
                currentTab = 'tab-1';
                newTab = 'tab-2';
              }
              const elementTab = document.querySelector('[data-id="#' + newTab + '"]');
              $('.tab-pane').removeClass('show active');
              $('.nav-tab').removeClass('active');
              $('#' + newTab).addClass('show active');
              $(elementTab).addClass('active');
              elementTab.focus();

              urlParams.set('tab', newTab);
              const modifiedUrl = window.location.pathname + '?' + urlParams.toString();
              window.history.replaceState({}, '', modifiedUrl);

            }
          });
        });
      </script>
    @endsection
