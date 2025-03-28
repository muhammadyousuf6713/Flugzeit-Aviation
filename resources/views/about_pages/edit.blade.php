@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Edit About Page</h1>

    <form action="{{ route('about_pages.update', $aboutPage->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="sidebar_items">Sidebar Items (JSON)</label>
            <textarea name="sidebar_items" id="sidebar_items" class="form-control" rows="3">{{ old('sidebar_items', $aboutPage->sidebar_items) }}</textarea>
        </div>

        <div class="form-group">
            <label for="vision_mission">Vision & Mission (JSON)</label>
            <textarea name="vision_mission" id="vision_mission" class="form-control" rows="3">{{ old('vision_mission', $aboutPage->vision_mission) }}</textarea>
        </div>

        <div class="form-group">
            <label for="core_values">Core Values (JSON)</label>
            <textarea name="core_values" id="core_values" class="form-control" rows="3">{{ old('core_values', $aboutPage->core_values) }}</textarea>
        </div>

        <div class="form-group">
            <label for="hec_recognition">HEC Recognition (JSON)</label>
            <textarea name="hec_recognition" id="hec_recognition" class="form-control" rows="3">{{ old('hec_recognition', $aboutPage->hec_recognition) }}</textarea>
        </div>

        <div class="form-group">
            <label for="future_aspiration">Future Aspiration</label>
            <textarea name="future_aspiration" id="future_aspiration" class="form-control" rows="3">{{ old('future_aspiration', $aboutPage->future_aspiration) }}</textarea>
        </div>

        <div class="form-group">
            <label for="chancellor_message">Chancellor Message (JSON)</label>
            <textarea name="chancellor_message" id="chancellor_message" class="form-control" rows="3">{{ old('chancellor_message', $aboutPage->chancellor_message) }}</textarea>
        </div>

        <div class="form-group">
            <label for="vice_chancellor_message">Vice Chancellor Message (JSON)</label>
            <textarea name="vice_chancellor_message" id="vice_chancellor_message" class="form-control" rows="3">{{ old('vice_chancellor_message', $aboutPage->vice_chancellor_message) }}</textarea>
        </div>

        <div class="form-group">
            <label for="project_head_message">Project Head Message (JSON)</label>
            <textarea name="project_head_message" id="project_head_message" class="form-control" rows="3">{{ old('project_head_message', $aboutPage->project_head_message) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
