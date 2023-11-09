<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
    <!--begin::Title-->
    <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">{{$name_page}}</h1>
    <!--end::Title-->
    <!--begin::Separator-->
    <span class="h-20px border-gray-300 border-start mx-4"></span>
    <!--end::Separator-->
    <!--begin::Breadcrumb-->
    @include('layouts.breadcrumb', ['breadcrumbs' => $breadcrumbs])
    <!--end::Breadcrumb-->
</div>
