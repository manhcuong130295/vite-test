@if (!empty($subscriptionPlan) && $subscriptionPlan->id == 3)
  <div class="d-flex flex-sm-row flex-column">
    <form id="fanpage-form"
      class="line-container {{ !empty($facebookFanpage) && !empty($facebookFanpage->status) && $facebookFanpage->status == 1 ? 'disabled' : '' }}"
      method="post" action="{{ route('api.facebook-fanpage.create') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="messager-intergate-id" value="{{ $facebookFanpage->id ?? '' }}" />
      @if (!empty($facebookFanpage) && !empty($facebookFanpage->status) && $facebookFanpage->status == 1)
        <div class="alert alert-warning">
          <span class="ms-3 text-danger">Fanpage integration will take about 24 hours</span>
        </div>
      @endif
      <div class="mb-3">
        <label for="fanpage-name" class="form-label">Fanpage Name</label>
        <input value="{{ !empty($facebookFanpage) ? $facebookFanpage->name : '' }}" type="text"
          class="form-fanpage form-control"
          id="fanpage-name">
        <span id="error-fanpage_name" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="fanpage-id" class="form-label">Fanpage ID</label>
        <input value="{{ !empty($facebookFanpage) ? $facebookFanpage->page_id : '' }}" type="text"
          class="form-fanpage form-control"
          id="fanpage-id">
        <span id="error-fanpage_id" class="validation text-danger"></span>
      </div>
      <div class="mb-3">
        <label for="fanpage-access-token" class="form-label">Access Token</label>
        <input value="{{ !empty($facebookFanpage) ? $facebookFanpage->access_token : '' }}" type="password" autocomplete="on"
          class="form-fanpage form-control" id="fanpage-access-token">
        <span id="error-fanpage_access_token" class="validation text-danger"></span>
      </div>
      <div class="d-flex justify-content-end">
        <button id="btn-save-messager" type="button" class="form-provider btn btn-primary mx-1 mb-3">Save</button>
      </div>
    </form>
    <div class="line-container align-items-centers">
      <div class="mb-3">
        @if (!empty($facebookFanpage))
        <label for="zalo-icon" class="form-label mt-3">Webhook URL</label>
        <div class="d-flex align-items-center justify-content-between border w-full p-2 bg-slate-100 rounded">
            <span id="webhook-url" class="fs-2 text-break">{{route('api.messenger.webhook-verify', ['id' => $facebookFanpage->id])}}</span>
            <i class="ti ti-folders fs-6" onclick="copyWebhookUrlToClipboard('#webhook-url')"></i>
        </div>
        <label for="zalo-icon" class="form-label mt-3">Verify Code</label>
        <div class="d-flex align-items-center justify-content-between border w-full p-2 bg-slate-100 rounded">
            <span id="webhook-code" class="fs-2 text-break">{{ $facebookFanpage->id }}</span>
            <i class="ti ti-folders fs-6" onclick="copyWebhookUrlToClipboard('#webhook-code')"></i>
        </div>
        @endif
      </div>
    </div>
  </div>
  <div>
    @include('project.documents.messenger_webhook')
  </div>
@else
  <div class="alert alert-warning text-center">
    <span class="ms-3 text-left text-danger">
      This is a special feature exclusively available for the Premium packages. To use it, please select
      the <a href="{{ route('payment.list') }}" aria-expanded="false" style="
        pointer-events: auto;">
        Upgrade</a>.
    </span>
  </div>
@endif
