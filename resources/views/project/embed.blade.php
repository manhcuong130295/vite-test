<section class="py-5 header">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-2">
                <!-- Tabs nav -->
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-embed" role="tablist" aria-orientation="vertical">
                    <a class="nav-link nav-link-left mb-3 p-3 shadow active" id="v-pills-embed" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-embed" aria-selected="true">
                        <i class="ti ti-sdk mr-2 fs-4"></i>
                        <span class="font-weight-bold small text-uppercase">Embed on site</span></a>
                    <a class="nav-link nav-link-left mb-3 p-3 shadow" id="v-pills-interface" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-interface" aria-selected="false">
                        <i class="ti ti-brand-hipchat mr-2 fs-4"></i>
                        <span class="font-weight-bold small text-uppercase">Chat interface</span></a>
                    </div>
            </div>
            <div class="col-md-10">
                <!-- Tabs content -->
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane tab-pane-left fade rounded bg-white show active p-3" id="v-pills-embed-panel" role="tabpanel" aria-labelledby="v-pills-embed-tab">
                        <div class="container py-3 border">
                            <div class="mt-2" style="text-align: center;">
                                <p class="text-sm text-gray-500">To add a chat bubble to the bottom right of your website add this script tag to your html</p>
                            </div>
                            <div class="mb-3">
                            <div class="row justify-content-center" style="">
                                <div class="position-relative col-md-6">
                                    <pre class=" w-full overflow-auto text-xs bg-slate-100 rounded p-2"><code id="p1"> &lt;script src="{{ asset('/sdk/embedded.min.js') }}"
projectId="{{ $project->id }}"
domain="{{ route('home') }}"
defer&gt;
&lt;/script&gt;</code></pre>
                                <div class="col-12 fs-5 lg:col-auto order-last lg:order-first d-flex justify-content-center">
                                    <a onclick="copyToClipboard('#p1')" href="javascript:void(0)" id="copy_embed" class="btn btn-light fs-2 lg:w-auto">
                                        Copy Script<i id="icon-copy" class="ti ti-folders ms-2"></i>
                                    </a>
                                </div>
                                </div>
                                <div class="col-md-6" style="width: 300px">
                                    <img src="{{ asset('assets/images/chatbot_bubble.png') }}" width="100%" height="100%"/>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="tab-pane tab-pane-left fade rounded bg-white p-3" id="v-pills-interface-panel" role="tabpanel" aria-labelledby="v-pills-interface-tab">
                        @if (!empty($subscriptionPlan) && $subscriptionPlan->id == 3)
                            @include('project.chat_interface')
                        @else
                        <div class="d-none">
                            @include('project.chat_interface')
                        </div>
                        <div class="alert alert-warning text-center">
                            <span class="ms-3 text-left text-danger">
                                This is a special feature exclusively available for the Premium packages. To use it, please select the <a href="{{ route('payment.list')}}" aria-expanded="false" style="
                                pointer-events: auto;"> Upgrade</a>.
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
