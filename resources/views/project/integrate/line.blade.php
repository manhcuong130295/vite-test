@if (!empty($subscriptionPlan) && $subscriptionPlan->id != 1)
  <div class="d-flex flex-sm-row flex-column">
    <form id="line-form"
      class="line-container {{ !empty($lineIntegrate) && !empty($lineIntegrate->status) && $lineIntegrate->status == 1 ? 'disabled' : '' }}"
      method="post" action="{{ route('api.line.create') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="project-id" value="{{ $project->id ?? '' }}" />
      <input type="hidden" id="provider-id" value="{{ $lineIntegrate->id ?? '' }}" />
      @if (!empty($lineIntegrate) && !empty($lineIntegrate->status) && $lineIntegrate->status == 1)
        <div class="alert alert-warning">
          <span class="ms-3 text-danger">Channel integration will take about 24 hours</span>
        </div>
      @endif
      <div class="mb-3">
        <label for="provider-name" class="form-label">Channel Name</label>
        <input value="{{ !empty($lineIntegrate) ? $lineIntegrate->provider_name : '' }}" type="text"
          class="form-provider form-control {{ !empty($lineIntegrate) && !empty($lineIntegrate->status) && $lineIntegrate->status == 2 ? 'disabled' : '' }}"
          id="provider-name">
        <span id="error-provider_name" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="provider-name" class="form-label">Channel Description</label>
        <input value="{{ !empty($lineIntegrate) ? $lineIntegrate->provider_description : '' }}" type="text"
          class="form-provider form-control" id="provider-description">
        <span id="error-provider_description" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="provider-icon" class="form-label">Channel Icon</label>
        <input value="" type="file"
          class="form-provider form-control mb-3 {{ !empty($lineIntegrate) && !empty($lineIntegrate->status) && $lineIntegrate->status == 2 ? 'disabled' : '' }}"
          id="provider-icon">
        @if (!empty($lineIntegrate) && !empty($lineIntegrate->path_icon))
          <div class="position-relative icon-provider">
            <img src="{{ asset($lineIntegrate->path_icon) }}" />
          </div>
        @endif
        <span id="error-provider_icon" class="validation text-danger"></span>
      </div>
      <div class="d-flex justify-content-end">
        <button id="btn-save-line" type="button" class="form-provider btn btn-primary mx-1 mb-3">Save</button>
      </div>
    </form>
    <div class="line-container">
      <div class="mb-3">
        <label for="provider-icon" class="form-label">QR CODE</label>
        @if (!empty($lineIntegrate) && !empty($lineIntegrate->path_qr_code))
          <div class="position-relative icon-provider qr-code-provider mx-auto ">
            <img src="{{ asset($lineIntegrate->path_qr_code) }}" />
          </div>
        @endif
      </div>
    </div>
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
