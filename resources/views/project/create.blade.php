@extends('layouts/app')
@section('title', 'Project')
@section('links-css')
  @include('project.asset.css_create')
@endsection
@section('content')
  <ul class="nav nav-tabs mb-3 mt-5 {{ !isset($project) ? 'd-none' : '' }} align-items-center" id="ex1" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link nav-tab active" data-bs-toggle="tab" data-id="#tab-1" href="javascript:void(0)" role="tab"
        aria-controls="ex1-tabs-1" aria-selected="true">Source
        @if (!empty($project))
          @if ($project->processing_status == 1)
            <i class="ti ms-1 ti-circle-check-filled" data-bs-toggle="tooltip"
              title="Source has been processed successfully." style="color: forestgreen !important"></i>
          @else
            <i class="ti ms-1 spinner-border" data-bs-toggle="tooltip" title="Source is being processed."></i>
          @endif
        @else
          <i class="ti ms-1 ti-alert-circle-filled" data-bs-toggle="tooltip" title="Source has not been installed."></i>
        @endif
      </a>
    </li>
    <li class="nav-item {{ !isset($project) ? 'd-none' : '' }}" role="presentation">
      <a class="nav-link nav-tab" data-bs-toggle="tab" data-id="#tab-2" href="javascript:void(0)" role="tab"
        aria-controls="ex1-tabs-2" aria-selected="false">Chatbot
        @if (!empty($project))
          @if ($project->processing_status == 1)
            <i class="ti ms-1 ti-circle-check-filled" data-bs-toggle="tooltip"
              title="Source has been processed successfully." style="color: forestgreen !important"></i>
          @else
            <i class="ti ms-1 spinner-border" data-bs-toggle="tooltip" title="Source is being processed."></i>
          @endif
        @else
          <i class="ti ms-1 ti-alert-circle-filled" data-bs-toggle="tooltip" title="Source has not been installed."></i>
        @endif
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link nav-tab" data-bs-toggle="tab" data-id="#tab-3" href="javascript:void(0)" role="tab"
        aria-controls="ex1-tabs-3" aria-selected="false">Intergate
        <i class="ti ti-message-2-code ms-1" data-bs-toggle="tooltip"
        title="Click here to set up information for the integrations." style="color: forestgreen !important"></i>
      </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link nav-tab" data-mdb-toggle="tab" data-id="#tab-4" href="javascript:void(0)" role="tab"
          aria-controls="ex1-tabs-4" aria-selected="false">Embed on site
          <i class="ti ms-1 ti-pill" data-bs-toggle="tooltip" title="Copy the script to apply the chatbot to your website." style="color: forestgreen !important"></i>
        </a>
    </li>
    @if(!empty($project))
      <a class="text-danger ms-3 delete-project-btn" data-delete-id={{ $project->id  }} data-project-name={{ $project->name }} name="delete-project" href="javascript:void(0)">
        Delete
        <i class="ti ms-1 ti-trash text-danger delete-project-btn" data-bs-toggle="tooltip" title="Delete your project."></i>
      </a>
    @endif
  </ul>
  <!-- Tabs navs -->
  <!-- Tabs content -->
  <div class="tab-content" id="ex1-content">
    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="ex1-tab-1">
      @include('project.modify')
    </div>
    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="ex1-tab-2">
      @if(isset($project) && $project->processing_status == 1)
        @include('project.chatbot')
      @else
        <div class="alert alert-warning disabled text-center">
          <span class="ms-3 text-danger">The data is currently being trained by AI, please wait until the process is complete.</span>
        </div>
      @endif
    </div>
    @if (isset($project))
      <div class="tab-pane fade" id="tab-3" role="tabpanel" aria-labelledby="ex1-tab-3">
        @include('project.integrate.index')
      </div>
    @endif
    @if (isset($project))
      <div class="tab-pane fade" id="tab-4" role="tabpanel" aria-labelledby="ex1-tab-4">
        @include('project.embed')
      </div>
    @endif
  <div>
@endsection
@section('links-script')
  @include('project.asset.js_create')
@endsection
