<div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('project.list') }}" class="text-nowrap logo-img mx-auto">
          <img src="{{ asset('/assets/images/logos/logo.png') }}" width="50" alt="" />
          <img class="ms-2" src="{{ asset('assets/images/logos/logo-home-black.png') }}" width="125px" alt="MGPT">
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-6"></i>
        </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Dashboard</span>
        </li>
        {{-- <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('payment.list') }}" aria-expanded="false">
            <span>
                <i class="ti ti-list"></i>
            </span>
            <span class="hide-menu">Register</span>
            </a>
        </li> --}}
        <li class="sidebar-item">
            <a class="sidebar-link" href="/project/create" aria-expanded="false">
            <span>
                <i class="ti ti-plus"></i>
            </span>
            <span class="hide-menu">Create Project</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link" href="/project/list" aria-expanded="false">
            <span>
                <i class="ti ti-list"></i>
            </span>
            <span class="hide-menu">My Projects</span>
            </a>
        </li>
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span id="subscription-plan-name" class="hide-menu">{{ isset($subscriptionPlan)? $subscriptionPlan->type : 'Free' }} Plan</span>
        </li>

        <li class="sidebar-item">
            <div class="card-sub-menu card border-start border-info">
                <div class="card-body card-sub-body">
                  <div class="row">
                    <div class="col-4">
                      <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                        <i class="ti ti-alphabet-greek fs-6"></i>
                      </div>
                    </div>
                    <div class="col-8 d-flex align-items-center justify-content-end text-end">
                      <div>
                        <h4 class="card-title">
                            {{ isset($subscriptionPlan) ? number_format($subscriptionPlan->max_character) : number_format(100000)}}
                        </h4>
                        <h6 class="fs-2  mb-0 fw-light">Limit characters</h6>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </li>
        <li class="sidebar-item">
            <div class="card-sub-menu card border-start border-info">
                <div class="card-sub-body card-body  ">
                  <div class="row">
                    <div class="col-4">
                      <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                        <i class="ti ti-briefcase fs-6"></i>
                      </div>
                    </div>
                    <div class="col-8 d-flex align-items-center justify-content-end text-end">
                      <div>
                        <h4 class="card-title">
                            <span 
                              class="{{ isset($subscriptionPlan) && $projectsCount < $subscriptionPlan->max_project ? 'text-info' : 'text-danger' }}"
                              >
                              {{ $projectsCount }}
                            </span>
                            <span 
                            class="fw-lighter">/</span>
                            {{ isset($subscriptionPlan) ? $subscriptionPlan->max_project : 1 }}
                        </h4>
                        <h6 class="fs-2 mb-0 fw-light">Limit projects</h6>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </li>
        <li class="sidebar-item">
          <div class="card-sub-menu card border-start border-info">
              <div class="card-sub-body card-body  ">
                <div class="row">
                  <div class="col-4">
                    <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                      <i class="ti ti-chart-line fs-6"></i>
                    </div>
                  </div>
                  <div class="col-8 d-flex align-items-center justify-content-end text-end">
                    <div class="w-full">
                      <h4 style="white-space: nowrap" class="card-title">
                          <span
                            class="{{ isset($subscriptionPlan) &&  auth()->user()->message_count < $subscriptionPlan->max_message ? 'text-info' : 'text-danger' }}"
                            >
                            {{ number_format(auth()->user()->message_count ?? 0) }}
                          </span>
                          <span class="fw-lighter">/</span>
                          {{ isset($subscriptionPlan) ? number_format($subscriptionPlan->max_message) : 40 }} 
                      </h4>
                      <h6 style="white-space: nowrap" class="fs-2 mb-0 fw-light">Message credits/month</h6>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </li>
        <li class="sidebar-item">
            <div class="card-sub-menu card border-start border-info">
                <div class="card-sub-body card-body  ">
                  <div class="row">
                    <div class="col-4">
                      <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                        <i class="ti ti-brand-hipchat fs-6"></i>
                      </div>
                    </div>
                    <div class="col-8 d-flex align-items-center justify-content-end text-end">
                      <div>
                        <h4 class="card-title">
                          <i class="ti fs-6 ms-1 ti-circle-check-filled" style="color: forestgreen !important"></i>
                        </h4>
                        <h6 class="fs-2 mb-0 fw-light">Chat integration</h6>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </li>
        <li class="sidebar-item">
          <div class="card-sub-menu card border-start border-info">
              <div class="card-sub-body card-body  ">
                <div class="row">
                  <div class="col-4">
                    <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                      <i class="ti ti-settings-code fs-6"></i>
                    </div>
                  </div>
                  <div class="col-8 d-flex align-items-center justify-content-end text-end">
                    <div>
                      <h4 class="card-title">
                      @if(isset($subscriptionPlan) && $subscriptionPlan->id == 3)
                        <i class="ti fs-6 ms-1 ti-circle-check-filled" style="color: forestgreen !important"></i>
                      @else
                      <i class="ti fs-6 ms-1 ti-circle-x-filled text-danger"></i>
                      @endif
                      </h4>
                      <h6 style="white-space: nowrap" class="fs-2 mb-0 fw-light">Chatbot interface</h6>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </li>
        <li class="sidebar-item">
            <div class="card-sub-menu card border-start border-info">
                <div class="card-sub-body card-body  ">
                  <div class="row">
                    <div class="col-4">
                      <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                        <i class="ti ti-brand-line fs-6"></i>
                      </div>
                    </div>
                    <div class="col-8 d-flex align-items-center justify-content-end text-end">
                      <div>
                        <h4 class="card-title">
                        @if(isset($subscriptionPlan) && $subscriptionPlan->id !== 1)
                          <i class="ti fs-6 ms-1 ti-circle-check-filled" style="color: forestgreen !important"></i>
                        @else
                        <i class="ti fs-6 ms-1 ti-circle-x-filled text-danger"></i>
                        @endif
                        </h4>
                        <h6 class="fs-2 mb-0 fw-light">Line integration</h6>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </li>
        <li class="sidebar-item">
          <div class="card-sub-menu card border-start border-info">
              <div class="card-sub-body card-body  ">
                <div class="row">
                  <div class="col-4">
                    <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                      <i class="ti ti-brand-zwift fs-6"></i>
                    </div>
                  </div>
                  <div class="col-8 d-flex align-items-center justify-content-end text-end">
                    <div>
                      <h4 class="card-title">
                      @if(isset($subscriptionPlan) && $subscriptionPlan->id !== 1)
                        <i class="ti fs-6 ms-1 ti-circle-check-filled" style="color: forestgreen !important"></i>
                      @else
                      <i class="ti fs-6 ms-1 ti-circle-x-filled text-danger"></i>
                      @endif
                      </h4>
                      <h6 class="fs-2 mb-0 fw-light">Zalo integration</h6>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </li>
        <li class="sidebar-item">
          <div class="card-sub-menu card border-start border-info">
              <div class="card-sub-body card-body  ">
                <div class="row">
                  <div class="col-4">
                    <div class="bg-primary text-white rounded d-flex align-items-center p-6 justify-content-center">
                      <i class="ti ti-brand-messenger fs-6"></i>
                    </div>
                  </div>
                  <div class="col-8 d-flex align-items-center justify-content-end text-end">
                    <div>
                      <h4 class="card-title">
                      @if(isset($subscriptionPlan) && $subscriptionPlan->id == 3)
                        <i class="ti fs-6 ms-1 ti-circle-check-filled" style="color: forestgreen !important"></i>
                      @else
                      <i class="ti fs-6 ms-1 ti-circle-x-filled text-danger"></i>
                      @endif
                      </h4>
                      <h6 style="white-space: nowrap" class="fs-2 mb-0 fw-light">Messenger integration</h6>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </li>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
</div>
