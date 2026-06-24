@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Create About Page</h1>

    <form action="{{ route('about_pages.store') }}" method="POST">
        @csrf

        <!-- AboutPage Fields -->
        <div class="form-group">
            <label for="sidebar_items">Sidebar Items (JSON)</label>
            <textarea name="sidebar_items" id="sidebar_items" class="form-control" rows="3">{{ old('sidebar_items') }}</textarea>
        </div>

        <div class="form-group">
            <label for="vision_mission">Vision & Mission (JSON)</label>
            <textarea name="vision_mission" id="vision_mission" class="form-control" rows="3">{{ old('vision_mission') }}</textarea>
        </div>

        <div class="form-group">
            <label for="core_values">Core Values (JSON)</label>
            <textarea name="core_values" id="core_values" class="form-control" rows="3">{{ old('core_values') }}</textarea>
        </div>

        <div class="form-group">
            <label for="hec_recognition">HEC Recognition (JSON)</label>
            <textarea name="hec_recognition" id="hec_recognition" class="form-control" rows="3">{{ old('hec_recognition') }}</textarea>
        </div>

        <div class="form-group">
            <label for="future_aspiration">Future Aspiration</label>
            <textarea name="future_aspiration" id="future_aspiration" class="form-control" rows="3">{{ old('future_aspiration') }}</textarea>
        </div>

        <div class="form-group">
            <label for="chancellor_message">Chancellor Message (JSON)</label>
            <textarea name="chancellor_message" id="chancellor_message" class="form-control" rows="3">{{ old('chancellor_message') }}</textarea>
        </div>

        <div class="form-group">
            <label for="vice_chancellor_message">Vice Chancellor Message (JSON)</label>
            <textarea name="vice_chancellor_message" id="vice_chancellor_message" class="form-control" rows="3">{{ old('vice_chancellor_message') }}</textarea>
        </div>

        <div class="form-group">
            <label for="project_head_message">Project Head Message (JSON)</label>
            <textarea name="project_head_message" id="project_head_message" class="form-control" rows="3">{{ old('project_head_message') }}</textarea>
        </div>

        <!-- Slides Section -->
        <h3 class="mt-5">Slides</h3>
        <!-- Slide Toggle Button -->
        <button type="button" id="addSlideBtn" class="btn btn-secondary mb-3">
            Add Slide
        </button>

        <!-- Container for dynamically added slide groups -->
        <div id="slidesContainer"></div>

        <button type="submit" class="btn btn-primary mt-3">Create</button>
    </form>
</div>

<!-- Script for dynamic slides -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let slideIndex = 0;
    const slidesContainer = document.getElementById('slidesContainer');
    const addSlideBtn = document.getElementById('addSlideBtn');

    addSlideBtn.addEventListener('click', function() {
        // Create a new slide group container
        const slideGroup = document.createElement('div');
        slideGroup.classList.add('card', 'mb-3', 'p-3');
        slideGroup.innerHTML = `
            <h4>Slide <span class="slide-number">${slideIndex + 1}</span></h4>
            <div class="form-group">
                <label>Slide Type</label>
                <select name="slides[${slideIndex}][slide_type]" class="form-control">
                    <option value="message_from_deans">Message from Deans</option>
                    <option value="faculty_members">Faculty Members</option>
                    <option value="university_management">University Management</option>
                </select>
            </div>
            <div class="form-group">
                <label>Heading</label>
                <input type="text" name="slides[${slideIndex}][heading]" class="form-control">
            </div>
            <div class="form-group">
                <label>Position</label>
                <input type="text" name="slides[${slideIndex}][position]" class="form-control">
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="slides[${slideIndex}][name]" class="form-control">
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="slides[${slideIndex}][image]" class="form-control">
            </div>
            <div class="form-group">
                <label>Description 1</label>
                <textarea name="slides[${slideIndex}][description1]" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Description 2</label>
                <textarea name="slides[${slideIndex}][description2]" class="form-control" rows="3"></textarea>
            </div>
            <button type="button" class="btn btn-danger removeSlideBtn">Remove Slide</button>
        `;
        slidesContainer.appendChild(slideGroup);
        slideIndex++;

        // Add event listener for the Remove button
        slideGroup.querySelector('.removeSlideBtn').addEventListener('click', function() {
            slideGroup.remove();
        });
    });
});
</script>
@endsection
