@if (!empty($subscriptionPlan) && $subscriptionPlan->id != 1)
  <div class="d-flex flex-sm-row flex-column">
    <form id="zalo-form"
      class="line-container {{ !empty($zaloIntegrate) && !empty($zaloIntegrate->status) && $zaloIntegrate->status == 1 ? 'disabled' : '' }}"
      method="post" action="{{ route('api.zalo.create') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="zalo-id" value="{{ $zaloIntegrate->id ?? '' }}" />
      @if (!empty($zaloIntegrate) && !empty($zaloIntegrate->status) && $zaloIntegrate->status == 1)
        <div class="alert alert-warning">
          <span class="ms-3 text-danger">Channel integration will take about 24 hours</span>
        </div>
      @endif
      <div class="mb-3">
        <label for="zalo-oa-name" class="form-label">Official Account Name</label>
        <input value="{{ !empty($zaloIntegrate) ? $zaloIntegrate->name : '' }}" type="text"
          class="form-zalo form-control"
          id="zalo-name">
        <span id="error-zalo_name" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="zalo-oa-id" class="form-label">Official Account ID</label>
        <input value="{{ !empty($zaloIntegrate) ? $zaloIntegrate->oa_id : '' }}" type="text"
          class="form-zalo form-control"
          id="zalo-oa-id">
        <span id="error-oa_id" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="zalo-client-id" class="form-label">Client ID</label>
        <input value="{{ !empty($zaloIntegrate) ? $zaloIntegrate->client_id : '' }}" type="text"
          class="form-zalo form-control" id="zalo-client-id">
        <span id="error-client_id" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="zalo-client-secret" class="form-label">Client Secret</label>
        <input value="{{ !empty($zaloIntegrate) ? $zaloIntegrate->client_secret : '' }}" type="text"
          class="form-zalo form-control" id="zalo-client-secret">
        <span id="error-client_secret" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="zalo-access-token" class="form-label">Access Token</label>
        <input value="{{ !empty($zaloIntegrate) ? $zaloIntegrate->access_token : '' }}" type="password" autocomplete="on"
          class="form-zalo form-control" id="zalo-access-token">
        <span id="error-access_token" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="zalo-refresh-token" class="form-label">Refresh Token</label>
        <input value="{{ !empty($zaloIntegrate) ? $zaloIntegrate->refresh_token : '' }}" type="password" autocomplete="on"
          class="form-zalo form-control" id="zalo-refresh-token">
        <span id="error-refresh_token" class="validation text-danger"></span>
      </div>
      <div class="d-flex justify-content-end">
        <button id="btn-save-zalo" type="button" class="form-provider btn btn-primary mt-3">Save</button>
      </div>
    </form>
    <div class="line-container">
        <div class="mb-3">
            <a href="{{ asset('assets/document/Settup_zalo_developers.docx') }}" target="_blank">Document settup<i class="ti ti-click"></i></a>
        </div>
      <div class="mb-3">
        <div class="mb-3">
          @if (!empty($zaloIntegrate))
          <label for="zalo-icon" class="form-label">Webhook URL</label>
          <div class="d-flex align-items-center justify-content-between border w-full p-2 bg-slate-100 rounded">
              <span id="zalo-webhook-url" class="fs-2 text-break">{{route('api.zalo.webhook')}} ?id={{ $zaloIntegrate->uuid }}</span>
              <i class="ti ti-folders fs-6" onclick="copyWebhookUrlToClipboard('#zalo-webhook-url')"></i>
          </div>
          @endif
        </div>
        <label for="zalo-icon" class="form-label">QR CODE</label>
        @if (!empty($zaloIntegrate) && !empty($zaloIntegrate->path_qr_code))
          <div class="position-relative icon-provider qr-code-provider mx-auto ">
            <img src="{{ asset($zaloIntegrate->path_qr_code) }}" />
          </div>
        @endif
      </div>
    </div>
  </div>
  <div>
    @include('project.documents.zalo')
  </div>
@else
  <div class="alert alert-warning text-center">
    <span class="ms-3 text-left text-danger">
      This is a special feature exclusively available for the Standard and Premium packages. To use it, please select
      the <a href="{{ route('payment.list') }}" aria-expanded="false" style="
        pointer-events: auto;">
        Upgrade</a>.
    </span>
  </div>
@endif
