@extends('layouts.user_type.auth')

@section('content')


    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-center">
                            <h6>Administration Table</h6>
                            <div class="justify-content-between ">
                                <a href="{{ route('administration.create-header') }}" class="btn btn-primary text-white"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-3">
                                <table id="dataTable" class="table align-items-center mb-0 responsive">
                                    <thead>
                                        <tr>
                                            <th class="control">id</th>
                                            <!-- This enables the expand/collapse functionality -->
                                            <th class="all">Header Name</th>
                                            <th class="all">Status</th>
                                            <th class="">Actions</th>
                                            <th class="none">Contacts</th>
                                            <th class="none">Authors</th>
                                            <th class="none">Details</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($headers as $key => $header)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $header->name }}</td>
                                                <td>{{ $header->status == 1 ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <a href="{{ route('administration.edit', $header->id) }}"
                                                        class="btn btn-warning  "><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('administration.delete', $header->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger  "
                                                            onclick="return confirm('Are you sure you want to delete this?')"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>


                                                <!-- Displaying contacts related to the details -->
                                                <td>
                                                    {{-- @if ($header->details->isNotEmpty()) --}}
                                                    <a href="{{ route('administration.create-contact') }}?id={{ optional($header->details->first())->id ?? '' }}" class="btn btn-primary btn-xs mt-3">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    {{-- @else --}}
                                                        <!-- You can show a message if there are no details -->
                                                        {{-- <a href="{{ route('administration.create-contact') }}"
                                                            class="btn btn-primary btn-xs mt-3">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    @endif --}}



                                                    <hr>

                                                    <div class="responsive card">
                                                        <table class="table align-items-center mb-0 responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th>id</th>
                                                                    <th>Name</th>
                                                                    <th>Email</th>
                                                                    <th>Number</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            @foreach ($header->details as $detail)
                                                                @foreach ($detail->contacts as $key => $contact)
                                                                    <tr>

                                                                        <td>{{ $key + 1 }} - </td>
                                                                        <td><span>{{ $contact->name }}</span></td>
                                                                        <td><span>{{ $contact->email }} </span></td>
                                                                        <td><span>{{ $contact->number }} </span></td>
                                                                        <td>
                                                                            <a href="{{ route('administration.edit-contact', $contact->id) }}"
                                                                                class="btn btn-warning btn-xs "><i
                                                                                    class="fas fa-edit"></i></a>
                                                                            <form
                                                                                action="{{ route('administration.delete-contact', $contact->id) }}"
                                                                                method="POST" style="display:inline;">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                    class="btn btn-danger btn-xs"
                                                                                    onclick="return confirm('Are you sure you want to delete this?')"><i
                                                                                        class="fas fa-trash"></i></button>
                                                                            </form>

                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </td>

                                                <!-- Displaying authors related to the contacts -->
                                                <td>
                                                    @if ($header)
                                                        <a href="{{ route('administration.create-author') }}?id={{ $header->id }}"
                                                            class="btn btn-primary btn-xs mt-3">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    @else
                                                        <!-- You can show a message if there are no details -->
                                                        <a href="{{ route('administration.create-author') }}"
                                                            class="btn btn-primary btn-xs mt-3">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    @endif
                                                    <div class="responsive card">
                                                        <table>
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Email</th>
                                                                    {{-- <th>About</th> --}}
                                                                    <th>Number</th>
                                                                    <th>Action</th>
                                                                    {{-- <th>Address</th> --}}
                                                                    {{-- <th>Description</th> --}}
                                                                    {{-- <th>Department Name</th>  --}}
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($header->authors as $author)
                                                                    <tr>
                                                                        <td>{{ $author->name }}</td>
                                                                        <td>{{ $author->email }}</td>
                                                                        {{-- <td>{{ $author->about }}</td> --}}
                                                                        <td>{{ $author->number }}</td>
                                                                        <td>
                                                                            <a href="{{ route('administration.edit-author', $author->id) }}"
                                                                                class="btn btn-warning btn-xs "><i
                                                                                    class="fas fa-edit"></i></a>
                                                                            <form
                                                                                action="{{ route('administration.delete-author', $author->id) }}"
                                                                                method="POST" style="display:inline;">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                    class="btn btn-danger btn-xs"
                                                                                    onclick="return confirm('Are you sure you want to delete this?')"><i
                                                                                        class="fas fa-trash"></i></button>
                                                                            </form>

                                                                        </td>
                                                                        {{-- <td>{{ $author->address }}</td> --}}
                                                                        {{-- <td>{{ $author->description }}</td> --}}
                                                                        {{-- <td>{{ $author->depart_name }}</td> --}}
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>

                                                <!-- Displaying details of the header -->
                                                <td>
                                                    @foreach ($header->details as $detail)
                                                        <div class="responsive card">
                                                            <table>
                                                                <tr>
                                                                    <td>Title:</td>
                                                                    <td>Description:</td>
                                                                    <td>Action</td>
                                                                </tr>
                                                                <tr>

                                                                    <td>{{ $detail->title }}</td>
                                                                    <td> {!! $detail->description !!}</td>
                                                                    <td>
                                                                        <a href="{{ route('administration.edit-detail', $detail->id) }}"
                                                                            class="btn btn-warning btn-xs "><i
                                                                                class="fas fa-edit"></i></a>
                                                                        <form
                                                                            action="{{ route('administration.delete-detail', $detail->id) }}"
                                                                            method="POST" style="display:inline;">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-xs"
                                                                                onclick="return confirm('Are you sure you want to delete this?')"><i
                                                                                    class="fas fa-trash"></i></button>
                                                                        </form>

                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Include DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            // var table = $('#dataTable').DataTable({
            //     responsive: {
            //         details: {
            //             type: 'column', // Use a column for expandable rows
            //             target: 0 // First column
            //         }
            //     },
            //     columnDefs: [{
            //         className: 'control', // Add the control class to the first column
            //         orderable: false,
            //         targets: 0
            //     }],
            //     order: [1, 'asc'] // Default sort by the second column
            // });
            var table = $('#dataTable').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0
                }],
                order: [1, 'Desc']
            });
            var table2 = $('#dataTable2').DataTable({
                responsive: {
                    details: {
                        type: 'column'
                    }
                },
                columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0
                }],
                order: [1, 'Desc']
            });
        });
    </script>
@endsection
