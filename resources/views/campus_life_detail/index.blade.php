@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
  <div class="container-fluid py-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Campus Life Detail List</h6>
        <a href="{{ route('campus-life-detail.create') }}" class="btn btn-primary">Add New Detail</a>
      </div>
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Campus Life ID</th>
                <th>Name</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($details as $detail)
              <tr>
                <td>{{ $detail->id }}</td>
                <td>
                  @if($detail->image)
                    <img src="{{ asset('campus_life_images/' . $detail->image) }}" alt="{{ $detail->name }}" width="50" style="border-radius: 50%;">
                  @endif
                </td>
                <td>{{ $detail->academicProgram ? $detail->academicProgram->name : 'N/A' }}</td>
                <td>{{ $detail->name }}</td>
                <td>{{ $detail->title }}</td>
                <td>{{ Str::limit($detail->description, 20) }}</td>
                <td>
                  <a href="{{ route('campus-life-detail.edit', $detail->id) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('campus-life-detail.destroy', $detail->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){
  $('#dataTable').DataTable();
});
</script>
@endsection
