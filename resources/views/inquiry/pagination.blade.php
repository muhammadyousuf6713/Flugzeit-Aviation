 @forelse ($inquiry as $inc)
     <a href="#" class="tickets-card row mt-4">
         <div class="tickets-details col-lg-4 col-12">
             <div class="wrapper">
                 <h5>#{{ $inc->id_inquiry }} - {{ $inc->customer_name }}</h5>
                 <div class="badge badge-success">{{ $inc->inquiry_type_name }}</div>
             </div>
             <div class="wrapper text-muted d-none d-md-block">
                 <span>Assigned to</span>
                 <img class="assignee-avatar" src="{{asset('img/default_user.png')}}" alt="profile image">
                 @php
                     $user = App\User::where('id', $inc->created_by)->first();
                 @endphp
                 <span>{{ $user?->name }}</span>
                 <span><i class="typcn icon typcn-time"></i></span>
             </div>
         </div>
         <div class="tickets-details col-lg-4 col-12">
             <div class="wrapper">
                 <h5>Remarks: {{ $inc->remarks }}</h5>
             </div>
         </div>
         <div class="ticket-float col-lg-2 col-sm-6 d-none d-md-block">
             {{-- <img class="img-xs rounded-circle" src="../img/faces/face16.jpg" alt="profile image"> --}}
             <button class="btn btn-primary" onclick="getListData({{$inc->id_inquiry}})"><span class="">View Remarks</span></button>
         </div>
         {{-- <div class="ticket-float col-lg-2 col-sm-6 d-none d-md-block">
             <i class="category-icon typcn icon typcn-folder"></i>
             <span class="text-muted">Wireframe</span>
         </div> --}}
     </a>
 @empty
 @endforelse
 {!! $inquiry->links('pagination::bootstrap-4') !!}
