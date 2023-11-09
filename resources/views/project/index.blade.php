@extends('layouts/app')
@section('title', 'Project')
@section('links-css')
  <link rel="stylesheet" href="{{ asset('assets/projects/assets/css/index.css') }}" />
  <style></style>
@endsection
@section('content')
      <div class="container bg-white ma p-3 bg-body">
        @if(count($projects) == 0)
          <div class="p-2 border-bottom">
              <h5>Here’s how to get started</h5>
          </div>
          <a type="button" href="/project/create" class="btn btn-info mt-3">Create your first project</a>
        @else
          <div class="row mt-5">
            @foreach ($projects as $project)
              <div class="project-card col-md-6 col-lg-3">
                <a href="{{ route('project.update', ['id' => $project->id]) }}">
                <div class="card text-center alert-dismissible fade show alert p-3 card-hover bg-light-secondary" role="alert">
                  <div class="p-2 d-block">
                      <img src="{{ asset('assets/images/logos/logo.png')}}" width="75" class="img-fluid my-2">
                      <h4 class="my-3">
                        @if ($project->processing_status == 1)
                        <i class="ti ms-1 ti-circle-check-filled" data-bs-toggle="tooltip"
                          title="Source has been processed successfully." style="color: forestgreen !important"></i>
                        @else
                          <i class="ti fs-1 spinner-border" style="width:20px; height:20px" data-bs-toggle="tooltip" title="Source is being processed."></i>
                        @endif
                      </h4>
                      <h5 data-bs-toggle="tooltip" title="{{ $project->name }}" class="card-title mt-3 block-ellipsis">{{$project->name}}</h5>
                      <h6 class="fs-1 mt-3">
                          {{ $project->created_at->tz('UTC')->toDatetimeString()}}
                      </h6>
                  </div>
                </div>
                </a>
              </div>
            @endforeach
            </div>
          </div>
        @endif
      </div>
@endsection
@section('links-script')
  <script>

  </script>
@endsection
