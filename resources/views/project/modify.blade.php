<section class="py-5 header">
    @if (!isset($project) && $subscriptionPlan && $projectsCount >= $subscriptionPlan->max_project)
        <div class="alert alert-warning disabled text-center">
            <span class="ms-3 text-danger">You have exceeded the maximum number of projects allowed <span>( {{ count($projects )}}/{{ $subscriptionPlan->max_project }} project)</span>. Please <a href="{{ route('payment.list')}}" aria-expanded="false" style="pointer-events: auto;"> Upgrade</a> to Standard or Premium.
            </span>
        </div>
    @else
    <div class="container py-4">
            @if (!empty($project) && !$project->processing_status)
                <div class="alert alert-warning disabled text-center">
                    <span class="ms-3 text-danger">The data is currently being trained by AI, please wait until the process is complete.</span>
                </div>
            @endif
            <div class="row">
                <div class="col-md-2">
                    <!-- Tabs nav -->
                    <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tabType" role="tablist" aria-orientation="vertical">
                        <a class="nav-link nav-link-type mb-3 p-3 shadow active" id="v-pills-text" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-text" aria-selected="true">
                            <i class="ti ti-align-center me-2 fs-6"></i>
                            <span class="font-weight-bold small text-uppercase">Text</span>
                        </a>
                        <a class="nav-link nav-link-type mb-3 p-3 shadow" id="v-pills-file" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-file" aria-selected="false">
                            <i class="ti ti-file-upload me-2 fs-6"></i>
                            <span class="font-weight-bold small text-uppercase">File</span>
                        </a>
                        <a class="nav-link nav-link-type mb-3 p-3 shadow" id="v-pills-crawl" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="v-pills-crawl" aria-selected="false">
                            <i class="ti ti-world-www me-2 fs-6"></i>
                            <span class="font-weight-bold small text-uppercase">Website</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 py-3 border">
                    <!-- Tabs content -->
                    <div class="p-3">
                        <label for="project-name" class="form-label">Project Name</label>
                        <input value="{{ isset($project) ? $project->name : '' }}" type="text" class="form-project form-control" id="project-name">
                        <span id="error-name" class="validation text-danger mt-2"></span>
                    </div>
                    <div class="tab-content" id="v-pills-tabTypeContent">
                        <div class="tab-pane tab-pane-type fade rounded bg-white show active p-3" id="v-pills-text-panel" role="tabpanel" aria-labelledby="v-pills-text-tab">
                            <div class="mb-1 text-center">
                                <h5 class="mb-0">Text</h5>
                            </div>
                            <div id="text-form">
                                @if(isset($project) && count($textContents) > 0)
                                    @foreach ($textContents as $content)
                                        <div class="form-content">
                                            <div class="detail-content alert alert-secondary d-flex justify-content-between align-items-center">
                                                <span>{{ $content->name }} ({{ number_format( mb_strlen($content->text)) }} characters)</span>
                                                <i name="show-form" class="ti ti-caret-right-filled fs-6 cusor-pointer"></i>
                                                <i name="hide-form" class="ti ti-caret-up-filled fs-6 cusor-pointer d-none"></i>
                                            </div>
                                            <div class="form-modify {{!empty($project) && !$project->processing_status ? 'disabled-form' : ''}} d-none">
                                                <div class="mb-3">
                                                    <label class="form-label">Page</label>
                                                    <input value="{{ $content->name }}" type="text" class="form-project form-control page-name-input" oninput="updateArrayInput(this)">
                                                    <span class="error-page-name validation text-danger mt-2"></span>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Content</label>
                                                    <textarea rows="15" class="form-project form-control project-content-input" oninput="updateArrayInput(this)">{{ $content->text }}</textarea>
                                                    <span class="error-page-content validation text-danger mt-2"></span>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <div class="justify-content-end">
                                                        <div class="mb-3 d-flex">
                                                            <button class="btn btn-danger mx-1" type="button" style="display: none" onclick="removeForm(this)">
                                                                <i class="ti ti-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-content">
                                        <div class="detail-content alert alert-secondary d-flex justify-content-between align-items-center">
                                            <span>Let create content for page to training.</span>
                                            <i name="show-form" class="ti ti-caret-right-filled fs-6 cusor-pointer d-none"></i>
                                            <i name="hide-form" class="ti ti-caret-up-filled fs-6 cusor-pointer"></i>
                                        </div>
                                        <div class="form-modify">
                                            <div class="mb-3">
                                                <label class="form-label">Page</label>
                                                <input type="text" class="form-project form-control page-name-input" oninput="updateArrayInput(this)">
                                                <span class="error-page-name validation text-danger mt-2"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Content</label>
                                                <textarea rows="15" class="form-project form-control project-content-input" oninput="updateArrayInput(this)"></textarea>
                                                <span class="error-page-content validation text-danger mt-2"></span>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <div class="justify-content-end">
                                                    <div class="mb-3 d-flex">
                                                        <button class="btn btn-danger mx-1" type="button" style="display: none" onclick="removeForm(this)">
                                                            <i class="ti ti-minus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="justify-content-end mb-3 d-flex">
                                <button class="btn btn-secondary font-weight-medium waves-effect waves-light mx-1" type="button" onclick="addForm()">
                                    <i class="ti ti-circle-plus fs-5"></i>
                                </button>
                            </div>
                            <span id="error-contents-text" class="text-danger"></span>
                        </div>
                        <div class="tab-pane tab-pane-type fade rounded bg-white p-3" id="v-pills-file-panel" role="tabpanel" aria-labelledby="v-pills-file-tab">
                            <div class="mb-1 text-center">
                                <h5 class="mb-0">File</h5>
                            </div>
                            <div class="{{!empty($project) && !$project->processing_status ? 'disabled-form' : ''}}">
                                <div class="mb-3">
                                    <label for="project-content" class="form-label">Project Content</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <label for="fileInput" class="text-center drop-area" id="dropArea">
                                                <input accept="application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="d-none" type="file" id="fileInput" class="file-input" multiple>
                                                <p>Drag and drop files here or click to select files</p>
                                                <p class="fs-1"> Supported File Types: .pdf, .docx </p>
                                                <div for="fileInput" class="file-label d-block text-center text-success">
                                                    <i class="ti ti-cloud-upload fs-6"></i>
                                                </div>
                                            </label>
                                            <div id="fileList" class="mt-3 max-h-36 overflow-auto file-list">
                                                @if(isset($project) && count($files) > 0)
                                                    @foreach($files as $file)
                                                        <div data-file-name="{{ $file->name }}"  class="alert alert-secondary d-flex justify-content-between align-items-center">
                                                            <span>{{ $file->name }} ({{ number_format( mb_strlen($file->text)) }} characters)</span>
                                                            <i name="delete-file" class="ti ti-trash fs-6 text-danger cusor-pointer" data-file-name="{{ $file->name }}"></i>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                    <div class="toast-header">
                                        <i style="color: greenyellow !important" class="ti ti-circle-check-filled me-2"></i>
                                        <strong class="me-auto">Project Save Success</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                    </div>
                                    <div class="toast-body">
                                        See? Just like this.
                                    </div>
                                </div>
                            </div>
                            <span id="error-contents-file" class="text-danger"></span>
                        </div>
                        @include('project.crawl.crawl')
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card alert-dismissible fade show alert p-0 card-hover bg-white" role="alert">
                        <div class="p-2 d-block mt-3">
                            <h5 class="card-title text-center">{{ isset($project) ? $project->name . "Source" : "Source" }}</h5>
                            <p class="fs-2" id="total-file-chars"></p>
                            <p class="fs-2" id="total-text-chars"></p>
                            <p class="fs-2" id="total-link-chars"></p>
                            <span class="fs-3 fw-bold">Total detected characters</span>
                            <p class="text-center fs-2">
                              <span id="total-length">0</span> /
                              {{ isset($subscriptionPlan) ? number_format($subscriptionPlan->max_character) : number_format(100000) }}
                            </p>
                            <p id="total-detect-chars-error" class="fs-2 text-danger text-center my-2"></p>
                            @if (isset($project))
                            <button id="update-button" type="button" class="modify-project-btn form-project btn btn-outline-primary d-block w-100">Retraining</button>
                            @else
                            <button id="save-button" type="button" class="modify-project-btn form-project btn btn-outline-primary d-block w-100">Create</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
