<section class="py-5 header">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-2">
                <!-- Tabs nav -->
                <div class="nav flex-column nav-pills nav-pills-custom" id="nav-pills-integrate" role="tablist" aria-orientation="vertical">
                    <a class="nav-link nav-link-integrate mb-3 p-3 shadow active" id="v-pills-line" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-line" aria-selected="true">
                        @if (!empty($lineIntegrate))
                            @if ($lineIntegrate->status == 2)
                                <i class="ti mr-2 ti-circle-check-filled" data-bs-toggle="tooltip"
                                title="Line has been processed successfully." style="color: forestgreen !important"></i>
                            @else
                                <i class="ti mr-2 spinner-border" data-bs-toggle="tooltip" title="Line is being processed."></i>
                            @endif
                        @else
                            <i class="ti mr-2 ti-alert-circle-filled" data-bs-toggle="tooltip" title="Line has not been installed."></i>
                        @endif
                        <span class="font-weight-bold small text-uppercase">Line</span></a>
                    <a class="nav-link nav-link-integrate mb-3 p-3 shadow" id="v-pills-line" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-zalo" aria-selected="false">
                        @if (!empty($zaloIntegrate))
                            @if ($zaloIntegrate->status == 2)
                                <i class="ti mr-2 ti-circle-check-filled" data-bs-toggle="tooltip"
                                title="Zalo has been processed successfully." style="color: forestgreen !important"></i>
                            @else
                                <i class="ti mr-2 spinner-border" data-bs-toggle="tooltip" title="Zalo is being processed."></i>
                            @endif
                        @else
                            <i class="ti mr-2 ti-alert-circle-filled" data-bs-toggle="tooltip" title="Zalo has not been installed."></i>
                        @endif
                        <span class="font-weight-bold small text-uppercase">Zalo</span></a>
                    <a class="nav-link nav-link-integrate mb-3 p-3 shadow" id="v-pill-messenger" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-messenger" aria-selected="false">
                        @if (!empty($facebookFanpage))
                            @if ($facebookFanpage->status == 2)
                                <i class="ti mr-2 ti-circle-check-filled" data-bs-toggle="tooltip"
                                title="Messenger has been processed successfully." style="color: forestgreen !important"></i>
                            @else
                                <i class="ti mr-2 spinner-border" data-bs-toggle="tooltip" title="Messenger is being processed."></i>
                            @endif
                        @else
                            <i class="ti mr-2 ti-alert-circle-filled" data-bs-toggle="tooltip" title="Messenger has not been installed."></i>
                        @endif
                        <span class="font-weight-bold small text-uppercase">Messenger</span></a>
                    </div>
            </div>
            <div class="col-md-10">
                <!-- Tabs content -->
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane tab-pane-integrate fade rounded bg-white show active p-3" id="v-pills-line-panel" role="tabpanel" aria-labelledby="v-pills-line-tab">
                        @include('project.integrate.line')
                    </div>
                    <div class="tab-pane tab-pane-integrate fade rounded bg-white p-3" id="v-pills-zalo-panel" role="tabpanel" aria-labelledby="v-pills-zalo-tab">
                        @include('project.integrate.zalo')
                    </div>
                    <div class="tab-pane tab-pane-integrate fade rounded bg-white p-3" id="v-pills-messenger-panel" role="tabpanel" aria-labelledby="v-pills-messenger-tab">
                        @include('project.integrate.messenger')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
