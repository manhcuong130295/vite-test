<div class="card position-sticky overflow-hidden chat-application">
  <div class="d-container">
    <div class="w-70 w-xs-100 chat-container border">
      <div class="chat-box-inner-part h-100">
        <div class="chatting-box d-block">
          <div class="position-relative overflow-hidden d-flex">
            <div class="position-relative d-flex flex-grow-1 flex-column">
              <div class="chat-box p-9" style="height: calc(100vh - 300px)" data-simplebar="init">
                <div  class="simplebar-wrapper" style="margin: -20px;">
                  <div class="simplebar-height-auto-observer-wrapper">
                      <div class="simplebar-height-auto-observer"></div>
                  </div>
                  <div class="simplebar-mask">
                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                      <div id="chat-box-content" class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 20px;">
                          <div id="msg_history" class="chat-list chat active-chat" data-user-id="1">
                            <div class="hstack gap-3 align-items-start mb-7 justify-content-start">
                              <img src="{{ asset('/assets/images/logos/logo.png') }}" alt="user8" width="40" height="40">
                              <div>
                                <h6 class="fs-2 text-muted">{{ $project->name }} Assistant</h6>
                                <div class="p-2 bg-light rounded-1 d-inline-block text-dark fs-3">I am a AI Assistant, Ask me anything, I will help answer your questions based on my understanding. </div>
                              </div>
                            </div>
                            {{-- <div class="hstack gap-3 align-items-start mb-7 justify-content-end">
                              <div class="text-end">
                                <h6 class="fs-2 text-muted">2 hours ago</h6>
                                <div class="p-2 bg-light-info text-dark rounded-1 d-inline-block fs-3"> If I don’t like something, I’ll stay away from it. </div>
                              </div>
                            </div> --}}
                          </div>
                          <!-- 2 -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="simplebar-placeholder" style="width: auto; height: 577px;"></div>
                </div>
                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div>
                <div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 241px; transform: translate3d(0px, 132px, 0px); display: block;"></div></div>
              </div>
              <div class="px-3 py-6 border-top chat-send-message-footer">
                <form id="form-question">
                  @csrf
                  <div class="position-relative d-flex align-items-center">
                    <textarea rows="2" id="question-text" required="" type="text" name="input" placeholder="Type your question here!" class="form-control border message-type-box text-muted p-2" 
                    style="height: 2rem;
                           box-sizing: initial;"></textarea>
                           <i id="send-message-icon" class="position-absolute ti ti-brand-telegram" style="right: 1rem;font-size: 1.5rem;color: dodgerblue;"></i>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
