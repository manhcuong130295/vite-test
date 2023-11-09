@extends('layouts/app')
@section('title', 'Pricing')
@section('links-css')
  <link rel="stylesheet" href="{{ asset('assets/pricing/assets/css/style.css') }}" />
  <style></style>
@endsection
@section('content')
  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif
  @if ($message = Session::get('message'))
    <div class="alert alert-danger">
        {{$message}}
    </div>
  @endif
  <div class="card">
    <h1 class="fs-10 fw-medium text-center mb-2 mt-0 mt-md-4">Pricing Plans</h1>
    <p class="text-center fs-5 mb-4">Ready To Build Your Own MGPT With Your Data?</p>
  </div>
  <div class="row">
    @if ($subscription)
      @foreach ($subscription as $sub)
        <div class="col-sm-6 col-lg-4">
          <div class="card {{ ((!empty($customer) && $customer->subscription_plan_id == $sub->id) || (empty($customer) && $sub->id == 1)) ? 'sub-active disabled' : '' }}">
            <div class="card-body  {{ $sub->id != 2 ? '' : 'pt-8' }}">
              <div class="text-end {{ $sub->id != 2 ? 'd-none' : '' }}">
                <span
                  class="badge fw-bolder py-1 bg-light-warning text-warning text-uppercase fs-2 rounded-3">POPULAR</span>
              </div>
              <span class="fw-bolder text-uppercase fs-2 d-block mb-7">{{ $sub->type }}</span>
              <div class="my-4">
                <img src="{{ asset($sub->image_path) }}" alt="" class="img-fluid" width="80"
                  height="80">
              </div>
              @if (!empty($sub->price))
                <div class="d-flex mb-3">
                  <h5 class="fw-bolder fs-6 mb-0">$</h5>
                  <h2 class="fw-bolder fs-12 ms-2 mb-0">{{ number_format($sub->price, 2) }}</h2>
                  <span class="ms-2 fs-4 d-flex align-items-center">/mo</span>
                </div>
              @else
                <h2 class="fw-bolder fs-12 mb-3">Free</h2>
              @endif
              <ul class="list-unstyled mb-7">
                <li class="d-flex align-items-center gap-2 py-2">
                  <i class="ti ti-check text-primary fs-4"></i>
                  <span class="text-dark">{{ number_format($sub->max_project) }} projects</span>
                </li>
                <li class="d-flex align-items-center gap-2 py-2">
                  <i class="ti ti-check text-primary fs-4"></i>
                  <span class="text-dark">{{ number_format($sub->max_character) }} characters/Project</span>
                </li>
                <li class="d-flex align-items-center gap-2 py-2">
                    <i class="ti ti-check text-primary fs-4"></i>
                    <span class="text-dark">{{ number_format($sub->max_message) }} messages/Month</span>
                  </li>
              </ul>
              <form action="{{ route('stripe.checkout') }}" method="post">
                @csrf
                <input hidden name="price_id" value="{{ $sub->id }}">
                <button type="submit" class="btn btn-primary fw-bolder rounded-2 py-6 w-100 text-capitalize">
                  @if((!empty($customer) && $customer->subscription_plan_id == $sub->id) || (empty($customer) && $sub->id == 1))
                    Signed In
                  @else
                    Subscribe
                  @endif
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    @endif
  </div>
  <div class="row mb-9">
    <div class="row-body">
        <div class="v-card-text text-center mt-16 fs-10">
            <h4 class="text-h4 mb-5"> Pick a plan that works best for you </h4>
          </div>
          <div class="v-card-text mb-16 mt-2">
            <div class="v-row">
              <div class="v-col-md-10 v-col-12 mx-auto">
                <div class="v-table v-theme--CustomGPT v-table--density-default text-no-wrap border rounded"><!---->
                  <div class="v-table__wrapper">
                    <table class="table border text-nowrap customize-table mb-0 align-middle">
                      <thead class="text-dark fs-4">
                        <tr>
                          <th scope="col" class="py-4">
                            <h6 class="text-sm font-weight-semibold mb-1"> FEATURES </h6>
                          </th>
                          <th scope="col" class="text-center py-4">
                            <h6 class="text-sm font-weight-semibold mb-1"> Free </h6><span
                              class="text-disabled font-weight-regular text-sm"> $0/month </span>
                          </th>
                          <th scope="col" class="text-center py-4">
                            <h6 class="text-sm font-weight-semibold mb-1"> Standard </h6><span
                              class="text-disabled font-weight-regular text-sm"> $39/month </span>
                          </th>
                          <th scope="col" class="text-center py-4">
                            <h6 class="text-sm font-weight-semibold mb-1"> Premium </h6><span
                              class="text-disabled font-weight-regular text-sm"> $99/month </span>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Custom chatbots using Open AI technology and business data</td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><i class="v-icon notranslate v-theme--CustomGPT" aria-hidden="true"
                                  style="font-size: 15px; height: 15px; width: 15px;"><!----><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" tag="i"
                                    class="v-icon notranslate v-theme--light iconify iconify--tabler" width="1em"
                                    height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m5 12l5 5L20 7"></path>
                                  </svg></i></div><!----><!---->
                            </span></td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" tag="i"
                                  class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler" width="1em"
                                  height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span></td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                  tag="i" class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler"
                                  width="1em" height="1em" viewBox="0 0 24 24"
                                  style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span></td>
                        </tr>
                        <tr>
                          <td>Chatbot interface</td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-secondary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><i class="v-icon notranslate v-theme--CustomGPT"
                                  aria-hidden="true" style="font-size: 15px; height: 15px; width: 15px;"><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" tag="i"
                                    class="v-icon notranslate v-theme--light iconify iconify--tabler" width="1em"
                                    height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                      stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12"></path>
                                  </svg><!----></i></div><!----><!---->
                            </span>
                          </td>
                          <td class="text-center"><span
                            class="v-chip v-chip--pill v-theme--CustomGPT text-secondary v-chip--density-default v-chip--variant-tonal pa-1"
                            draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                            <div class="v-chip__content"><i class="v-icon notranslate v-theme--CustomGPT"
                                aria-hidden="true" style="font-size: 15px; height: 15px; width: 15px;"><svg
                                  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                  aria-hidden="true" role="img" tag="i"
                                  class="v-icon notranslate v-theme--light iconify iconify--tabler" width="1em"
                                  height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12"></path>
                                </svg><!----></i></div><!----><!---->
                            </span>
                          </td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                  tag="i" class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler"
                                  width="1em" height="1em" viewBox="0 0 24 24"
                                  style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span>
                          </td>
                        </tr>
                        <tr>
                          <td>Line integration</td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-secondary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><i class="v-icon notranslate v-theme--CustomGPT"
                                  aria-hidden="true" style="font-size: 15px; height: 15px; width: 15px;"><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" tag="i"
                                    class="v-icon notranslate v-theme--light iconify iconify--tabler" width="1em"
                                    height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                      stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12"></path>
                                  </svg><!----></i></div><!----><!---->
                            </span>
                          </td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                  tag="i" class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler"
                                  width="1em" height="1em" viewBox="0 0 24 24"
                                  style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span></td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                  tag="i" class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler"
                                  width="1em" height="1em" viewBox="0 0 24 24"
                                  style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span></td>
                        </tr>
                        <tr>
                          <td>Zalo integration</td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-secondary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><i class="v-icon notranslate v-theme--CustomGPT"
                                  aria-hidden="true" style="font-size: 15px; height: 15px; width: 15px;"><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" tag="i"
                                    class="v-icon notranslate v-theme--light iconify iconify--tabler" width="1em"
                                    height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                      stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12"></path>
                                  </svg><!----></i></div><!----><!---->
                            </span>
                          </td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                  tag="i" class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler"
                                  width="1em" height="1em" viewBox="0 0 24 24"
                                  style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span></td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                  tag="i" class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler"
                                  width="1em" height="1em" viewBox="0 0 24 24"
                                  style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span></td>
                        </tr>
                        <tr>
                          <td>Messenger integration</td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-secondary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><i class="v-icon notranslate v-theme--CustomGPT"
                                  aria-hidden="true" style="font-size: 15px; height: 15px; width: 15px;"><svg
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    aria-hidden="true" role="img" tag="i"
                                    class="v-icon notranslate v-theme--light iconify iconify--tabler" width="1em"
                                    height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                      stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12"></path>
                                  </svg><!----></i></div><!----><!---->
                            </span>
                          </td>
                          <td class="text-center"><span
                            class="v-chip v-chip--pill v-theme--CustomGPT text-secondary v-chip--density-default v-chip--variant-tonal pa-1"
                            draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                            <div class="v-chip__content"><i class="v-icon notranslate v-theme--CustomGPT"
                                aria-hidden="true" style="font-size: 15px; height: 15px; width: 15px;"><svg
                                  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                  aria-hidden="true" role="img" tag="i"
                                  class="v-icon notranslate v-theme--light iconify iconify--tabler" width="1em"
                                  height="1em" viewBox="0 0 24 24" style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" d="M18 6L6 18M6 6l12 12"></path>
                                </svg><!----></i></div><!----><!---->
                            </span>
                          </td>
                          <td class="text-center"><span
                              class="v-chip v-chip--pill v-theme--CustomGPT text-primary v-chip--density-default v-chip--variant-tonal pa-1"
                              draggable="false"><!----><span class="v-chip__underlay"></span><!----><!---->
                              <div class="v-chip__content"><svg xmlns="http://www.w3.org/2000/svg"
                                  xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img"
                                  tag="i" class="v-icon notranslate v-theme--CustomGPT iconify iconify--tabler"
                                  width="1em" height="1em" viewBox="0 0 24 24"
                                  style="font-size: 15px; height: 15px; width: 15px;">
                                  <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m5 12l5 5L20 7"></path>
                                </svg></div><!----><!---->
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div><!---->
                </div>
              </div>
            </div>
          </div>
    </div>
  </div>

@endsection

@section('links-script')
  <script>
    setTimeout(function() {
      $(".alert").fadeOut();
    }, 5000);
  </script>
@endsection
