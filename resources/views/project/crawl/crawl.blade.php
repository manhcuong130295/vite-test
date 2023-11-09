<div class="tab-pane tab-pane-type fade rounded bg-white p-3" id="v-pills-crawl-panel" role="tabpanel" aria-labelledby="v-pills-crawl-tab">
    <div class="mb-1 text-center">
        <h5 class="mb-3">Website</h5>
    </div>
    <div id="project-form" class="{{!empty($project) && !$project->processing_status ? 'disabled-form' : ''}}">
        <div class="mb-3 border p-3">
            <div>
                <label class="form-label">Crawl</label>
                <div class="relative mt-2 rounded-md">
                    <div class="d-flex">
                        <input type="text" id="website" name="website" class="form-control me-2 " placeholder="https://www.example.com" value="">
                        <button id="btn_crawl" class="btn btn-primary col-4" type="button">Fetch more links</button>
                    </div>
                    <div id="progress_container" class="w-full rounded-full py-3 d-none">
                        <p id="progress_text" class="text-xs text-gray-600">0%</p>
                        <div id="progress_bar" class="progress bg-green h-05" style="width: 0%;"></div>
                    </div>
                    <div class="py-4 text-sm text-gray-600">This will crawl all the links starting with the URL (not including files on the website).</div>
                </div>
                <div id="include_links" class="mt-16">
                    <div class="d-flex align-items-center my-5">
                        <hr class="w-50 rounded">
                        <span class="px-2 w-50 text-center">Included Links</span>
                        <hr class="w-50 rounded">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button id="delete_all" type="button" class="btn btn-danger">Delete all</button>
                    </div>

                    <div class="max-h-36 overflow-auto mt-5 pr-2">
                        <div id="content-crawls" class="relative mt-2 rounded-md shadow-sm">
                            @php
                                $totalDetect = 0;
                            @endphp
                            @if(isset($project) && count($linkContents) > 0)
                                @foreach($linkContents as $linkContent)
                                    @php
                                        $totalDetect += mb_strlen($linkContent->text);
                                    @endphp
                                    <div class='py-3'>
                                        Link content:
                                        <a class='mx-3' target='_blank' href="{{ $linkContent->name }}">{{ $linkContent->name }}
                                        </a>
                                        <input type='hidden' name='link_content[]' value="{{ $linkContent->name }}"/>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <textarea rows="10" name="content[]" data-length=" {{ number_format(mb_strlen($linkContent->text)) }}" class="form-control">{{ $linkContent->text }}</textarea>
                                        <p class="mx-2 my-auto">
                                            {{ number_format(mb_strlen($linkContent->text) )}}
                                        </p>
                                        <button name="delete_link" data-delete-id={{ $linkContent->id }} type="button" class="border-none">
                                            <i class="ti ti-trash fs-6 text-danger cusor-pointer"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <p class="mt-4 fs-2"><span id="total_detect">{{ $totalDetect}}</span>&nbsp detected characters</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="error-contents-link" class="text-danger"></span>
</div>
