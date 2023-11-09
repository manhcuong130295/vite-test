<!--begin::Javascript-->
<!--begin::Global Javascript Bundle(used by all pages)-->
<script src="{{ asset(mix('assets/plugins/global/plugins.bundle.js')) }}"></script>
<script src="{{ asset(mix('assets/js/scripts.bundle.js')) }}"></script>
{{--<script src="{{ asset('assets/js/jquery.min.js') }}"></script>--}}
<!--end::Global Javascript Bundle-->

<!--begin::Vendor Javascript-->
@stack('vendor_script')
<!--end::Global Javascript-->

<!--begin::Page Javascript)-->
@stack('page_script')
<!--end::Page Javascript-->
<!--end::Javascript-->
<script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
