<div>
    <form id="chat_interface_form" action="{{ route('api.chat_interface.create') }}" enctype="multipart/form-data">
        <input type="hidden" id="project_id" value="{{ $project->id ?? '' }}" />
        <input type="hidden" id="chat_interface_id" value="{{ !empty($chatInterface) ? $chatInterface->id : '' }}" />
        @csrf
        <div class="border border-gray-200">
            <div class="border-bottom border-gray-200 bg-white py-3 px-5">
                <h3 class="text-xl font-semibold text-gray-900">Chat Interface</h3>
            </div>
            <div class="p-1 p-sm-5">
                <h4 class="mb-4 text-sm text-gray-600">Note: Applies when embedded on a website</h4>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="pb-4">
                            <label class="form-label">Display name</label>
                            <input type="text" id="bot_name" name="bot_name" class="form-control" value="{{ !empty($chatInterface) ? $chatInterface->chatbot_name : ''  }}">
                            <span id="error-bot_name" class="validation text-danger"></span>
                        </div>
                        <div class="pb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label">Theme</label>
                                <button type="button" id="theme_reset" class="btn btn-sm btn-primary">Reset</button>
                            </div>
                            <div class="mb-2">
                                <input type="color" class="form-control w-2 h-2 p-1 border bg-gray-100" value="{{ !empty($chatInterface) ? $chatInterface->theme_color : '#1F2937' }}" id="theme" name="theme">
                                <span id="error-theme" class="validation text-danger"></span>
                            </div>
                        </div>
                        <div id="display_icon" class="pb-4" style="{{ (!empty($chatInterface) && $chatInterface->chatbot_picture_active == 1) ? 'display: none' : ''}}">
                            <label class="form-label">Update chatbot profile picture</label>
                            <input id="bot_profile_picture" type="file" accept="image/*" name="bot_profile_picture" class="form-control">
                            <span id="error-bot_profile_picture" class="validation text-danger"></span>
                        </div>
                        <div class="pb-4">
                            <label class="form-label">Remove Chatbot Profile Picture</label>
                            <div class="form-check">
                                <input type="checkbox" {{ (!empty($chatInterface) && $chatInterface->chatbot_picture_active == 1) ? 'checked' : ''}} id="remove_bot_profile_picture" name="remove_bot_profile_picture" class="form-check-input">
                                <span id="error-remove_bot_profile_picture" class="validation text-danger"></span>
                            </div>
                        </div>
                        <div class="pb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <label class="form-label">Initial Messages</label>
                                <button type="button" id="initial_reset" class="btn btn-sm btn-primary">Reset</button>
                            </div>
                            <div class="mb-2">
                                <textarea id="initial_messages" name="initial_messages" class="form-control" rows="4" placeholder="Hi! What can I help you with?" maxlength="1000">{{ !empty($chatInterface) ? $chatInterface->initial_message : 'I am an AI Assistant, Ask me anything, I will help answer your questions based on my understanding.' }}</textarea>
                                <p class="mt-2 text-sm text-gray-600">Enter each message in a new line.</p>
                                <span id="error-initial_message" class="validation text-danger"></span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Suggested Messages</label>
                            <div class="mb-2">
                                <textarea id="suggested_messages" name="suggested_messages" class="form-control" rows="4" placeholder="What is example.com?">{{ !empty($chatInterface) ? $chatInterface->suggest_message : '' }}</textarea>
                                <p class="mt-2 text-sm text-gray-600">Enter each message in a new line.</p>
                                <span id="error-suggested_messages" class="validation text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-column flex-shrink-0 border rounded-top">
                            <div class="w-100">
                                <div id="chat_theme" class="d-flex justify-between py-1 mb-2 mh-50 rounded-top bg-gray-800 rounded-top text-white">
                                    <div class="d-flex align-items-center p-1 m-1">
                                        <img id="chat_bot_profile_picture" src="{{ (!empty($chatInterface) && $chatInterface->chatbot_picture) ? asset($chatInterface->chatbot_picture) : '' }}" width="35" height="35" decoding="async" data-nimg="1" class="rounded-circle mx-2" loading="lazy" alt="profile picture" style="{{ (!empty($chatInterface) && $chatInterface->chatbot_picture_active == 1) ? 'display: none' : '' }}" onerror="this.style.display='none';">
                                        <h1 id="chat_bot_name" class="h4 mx-2 fs-5 m-auto" style="color: #fff">{{ !empty($chatInterface) ? $chatInterface->chatbot_name : '' }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="h-26 overflow-auto">
                                <div class="pr-2 p-2">
                                    <div class="d-flex justify-content-start mb-3">
                                        <div id="chat_initial_messages" class="reply text-black white-break rounded-lg py-2 px-4 typing bg-gray-200">{{ !empty($chatInterface) ? $chatInterface->initial_message : 'I am an AI Assistant, Ask me anything, I will help answer your questions based on my understanding.' }}</div>
                                    </div>
                                    <div class="d-flex justify-content-end mb-3">
                                        <div class="text-white rounded-lg py-2 px-4 bg-question">
                                            Hi
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="container mt-4">
                                    <div id="suggest_mess" class="py-3 d-flex overflow-x-auto">
                                        @if (!empty($chatInterface) && !empty($chatInterface->suggest_message))
                                            @php
                                                $dataArray = explode("\r\n", $chatInterface->suggest_message);
                                                foreach ($dataArray as $key => $value) {
                                                    echo '<button type="button" class="rounded-xl border-0 whitespace-nowrap mx-1 mt-1 py-2 px-3 text-sm text-nowrap bg-zinc-100">' . $value . '</button>';
                                                }
                                            @endphp
                                        @endif
                                    </div>
                                </div>
                                <div id="chat-input-container" class="p-2 border-t border-gray-200">
                                    <div class="items-center d-flex">
                                        <input type="text" id="chat-input" class="min-w-0 flex-1 rounded-md px-4 py-2 outline-none border-none" placeholder="Type your message...">
                                        <button type="button" id="chat-submit" class="bg-gray-800 text-white rounded-md px-4 py-2 cursor-pointer border-0">
                                        <i class="ti ti-send" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="flex text-center text-xs pt-2">
                                        <span class="flex-1">Prompted by <a id="current_url" href="https://tomosia.com" target="_blank" class="text-indigo-600">MGPT</a></span>
                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end bg-gray-100 mt-3">
                <button id="update-chat-interface" type="button" class="modify-project-btn form-project btn btn-primary mx-2 mb-1 mt-1">Save</button>
            </div>
        </div>
    </form>
</div>
