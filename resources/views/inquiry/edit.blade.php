@extends('layouts.master')

@push('css')

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">Edit Inquiry - <b style="color:green">{{$edit_inquiry->customer_name}}</b> - {{$edit_inquiry->contact_1}} for Inquiry type: <i style="font-size:16px;color:orange">{{$edit_inquiry->inq_type}}</i> )</h3>
                    <a class="btn btn-success pull-right" href="{{url('inquiry')}}"><i class="icon-eye"></i>
                        &nbsp; View Inquiry List</a>
                    <div class="clearfix"></div>
                    <hr>
                    <form class="form-horizontal" method="post" action="{{url('update_inquiry/'.$edit_inquiry->id_inquiry)}}">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">

                                    <div class="form-group">
                                        <label for="inquiry_email" class="col-sm-3 control-label" style="color:green;font-weight:bold">Inquiry Details</label>
                                        <div class="col-sm-6">
                                            <p><?php echo $edit_inquiry->remarks;?></p>
                                            <hr>
                                        </div>
                                        <div class="col-sm-1">
                                            <p>~{{$edit_inquiry['saleperson']}} <label class="label label-info">{{date('d-m-Y H:i:s', strtotime($edit_inquiry->create_date))}}</label></p>
                                        </div>
                                    </div>
                                @if($saved_remarks)
                                @foreach($saved_remarks as $my_remarks)
                                    <div class="form-group">
                                        <label for="inquiry_email" class="col-sm-3 control-label" style="color:orange;font-weight:bold">Progress Remarks</label>
                                        <div class="col-sm-6">
                                            <?php echo $my_remarks['remarks'];?>
                                        </div>
                                        <div class="col-sm-1">
                                            <p>~{{$my_remarks['remarks_by']}} <label class="label label-info">{{date('d-m-Y H:i:s', strtotime($my_remarks['created_on']))}}</label></p>
                                        </div>
                                    </div>
                                <hr>
                                @endforeach
                                @endif


                                    <div class="form-group">
                                        <label for="remarks" class="col-sm-3 control-label">Progress Remarks<strong class="text-danger" id="progress_star"> *</strong></label>
                                        <div class="col-sm-7">
                                            <textarea id="progress_remarks" type="text" placeholder="Enter Remarks" class="form-control{{ $errors->has('remarks') ? ' is-invalid' : '' }}"
                                                      name="remarks" autofocus required></textarea>
                                            <span><span id="display_count">0</span> / 50</span>
                                        </div>
<!--                                        <div class="col-sm-2">
                                            <button id="addRow" type="button" class="btn btn-info"><i class="fa fa-comment"></i> Add More Remarks</button>

                                        </div>-->
                                    </div>
                                    <div class="form-group {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                        <label for="sale_person" class="col-sm-3 control-label">Sales Person<strong class="text-danger"> *</strong></label>
                                        <div class="col-sm-7">
                                            <select name="sale_person" id="status" class="form-control" required>
                                                <option value="">Please Select</option>
                                                  @if($sales_person)
                                                  @foreach($sales_person as $sale_p)
                                                    <option {{ $sale_p->name == $edit_inquiry->saleperson ? "selected" : "" }} value="{{$sale_p->name}}">{{$sale_p->name}}</option>
                                                  @endforeach
                                                  @endif
                                            </select>
                                            @if ($errors->has('sale_person'))
                                            <span class="invalid-feedback">
                                                <strong class="text-danger">{{ $errors->first('sale_person') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

<!--                                    <div class="form-group {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                        <label for="sales_reference" class="col-sm-3 control-label">Sales Reference<strong class="text-danger"> *</strong></label>
                                        <div class="col-sm-7">
                                            <select name="sales_reference" id="status" class="form-control" required>
                                                <option value="">Please Select</option>
                                                  @if($sales_reference)
                                                  @foreach($sales_reference as $sale_ref)
                                                    <option {{ $sale_ref->type_id == $edit_inquiry->sales_reference ? "selected" : "" }} value="{{$sale_ref->type_id}}">{{$sale_ref->type_name}}</option>
                                                  @endforeach
                                                  @endif
                                            </select>
                                            @if ($errors->has('sales_reference'))
                                            <span class="invalid-feedback">
                                                <strong class="text-danger">{{ $errors->first('sales_reference') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>-->

                                    <div class="form-group {{ $errors->has('status') ? ' is-invalid' : '' }}">
                                        <label for="status" class="col-sm-3 control-label">Status<strong class="text-danger"> *</strong></label>
                                        <div class="col-sm-7">
                                            <select name="status" id="status_row" class="form-control" onchange="status_change()" required>
                                                <option value="">Please Select</option>
                                                <option <?= $edit_inquiry->inquiry_status == 'Open' ? "selected" : "" ?> value="Open">Open</option>
                                                <option <?= $edit_inquiry->inquiry_status == 'In-Progress' ? "selected" : "" ?> value="In-Progress">In-Progress</option>
                                                <option <?= $edit_inquiry->inquiry_status == 'Completed' ? "selected" : "" ?> value="Completed">Completed</option>
                                                <option <?= $edit_inquiry->inquiry_status == 'Canceled' ? "selected" : "" ?> value="Canceled">Canceled</option>
                                                <option <?= $edit_inquiry->inquiry_status == 'Confirmed' ? "selected" : "" ?> value="Confirmed">Confirmed</option>
                                            </select>
                                            @if ($errors->has('status'))
                                            <span class="invalid-feedback">
                                                <strong class="text-danger">{{ $errors->first('status') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="newRow"></div>
                                    <div id="calculated_total"></div>

                                    <?php if(!empty($edit_inquiry->confirmed_amount)){
                                        $c_amount = $edit_inquiry->confirmed_amount;
                                        $cal_amount = $edit_inquiry->calculated_amount;
                                        $badge = '';
                                        $sign = '';
                                        $total = $c_amount - $cal_amount;

                                        if($cal_amount > $c_amount){
                                            $badge = 'badge-danger';
                                            $sign = '-';
                                        }else{
                                            $badge = 'badge-success';
                                        }
                                        ?>
                                    <div class="form-group" id="inputFormRow2">
                                        <label for="confirm_amount" class="col-sm-3 control-label">Confirmed Amount<strong class="text-danger"> *</strong></label>
                                        <div class="col-sm-2">
                                            <input type="text" id="confirmed_amount" onkeyup="calculated_amount_function()" name="confirmed_amount" class="form-control" placeholder="Enter Confirmed Amount" autocomplete="off" value="{{ $edit_inquiry->confirmed_amount }}" required><br>
                                        </div>
                                        <label for="confirm_amount" class="col-sm-2 control-label">Calculated Amount<strong class="text-danger"> *</strong></label>
                                        <div class="col-sm-3">
                                            <input type="text" id="calculated_amount" onkeyup="calculated_amount_function()" name="calculated_amount" class="form-control" placeholder="Enter Calculated Amount" autocomplete="off" value="{{ $edit_inquiry->calculated_amount }}" required><br>
                                        </div>
                                        <label for="saved_amount" class="col-sm-3 control-label">Total Saved Amount<strong class="text-danger"></strong></label>
                                        <div class="col-sm-7">
                                            <h4><badge class="badge <?= $badge ?>" id="total_amount"><?=$sign?>{{($total) }}</badge></h4><br>
                                        </div>
                                    </div>
                                    <?php }?>

                                    <?php if(!empty($edit_inquiry->cancel_reason)){
                                        $cancel_reason = '';
                                        $cancel_reason = $edit_inquiry->cancel_reason;
                                        ?>
                                        <div class="form-group" id="inputFormRow3">
                                        <label for="canceled_reason" class="col-sm-3 control-label">Cancel Reason<strong class="text-danger"> *</strong></label>
                                        <div class="col-sm-7">
                                        <select name="cancel_reason" class="form-control" autocomplete="off" required>
                                        <option <?= $cancel_reason == 'Plan Cancel' ? 'selected' : ''?>>Plan Cancel</option>
                                        <option <?= $cancel_reason == 'Purchased Online' ? 'selected' : ''?>>Purchased Online</option>
                                        <option <?= $cancel_reason == 'Other Travel Agent' ? 'selected' : ''?>>Other Travel Agent</option>
                                        <option <?= $cancel_reason == 'Plan Postponed' ? 'selected' : ''?>>Plan Postponed</option>
                                        <option <?= $cancel_reason == 'Number NR' ? 'selected' : ''?>>Number NR</option>
                                        <option <?= $cancel_reason == 'Expensive' ? 'selected' : ''?>>Expensive</option>
                                        <option <?= $cancel_reason == 'Wrong Number' ? 'selected' : ''?>>Wrong Number</option>
                                        <option <?= $cancel_reason == 'Office Location' ? 'selected' : ''?>>Office Location</option>
                                        <option <?= $cancel_reason == 'Other' ? 'selected' : ''?>>Other</option>
                                        </select>
                                        </div>
                                        </div>
                                        <?php } ?>

                                    <div class="form-group">
                                        <label for="customer_email" class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-7">
                                            <input type="text"
                                                   class="form-control{{ $errors->has('customer_email') ? ' is-invalid' : '' }}"
                                                   name="customer_email" value="{{ $edit_inquiry->customer_email }}" autofocus>
                                            @if ($errors->has('customer_email'))
                                            <span class="invalid-feedback">
                                                <strong class="text-danger">{{ $errors->first('customer_email') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                            </div>

                                @if ($errors->has('confirmed_amount'))
                                <div class="col-lg-7 col-lg-offset-3">
                                <span class="invalid-feedback">
                                    <strong class="text-danger">* {{ $errors->first('confirmed_amount') }}</strong>
                                </span>
                                </div>
                                @endif

                                @if ($errors->has('calculated_amount'))
                                <div class="col-lg-7 col-lg-offset-3">
                                <span class="invalid-feedback">
                                    <strong class="text-danger">* {{ $errors->first('calculated_amount') }}</strong>
                                </span>
                                </div>
                                @endif


                        </div>

                        <div class="form-group m-b-0">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#employee_id, #account_id, #inquiry_type, #city_id').select2();

    });
</script>
<script type="text/javascript">
    // add row




    function status_change(){
        var status_row = $("#status_row").val();

        if(status_row === 'Canceled'){
        $('#inputFormRow2').closest('#inputFormRow2').remove();

        var html = '';
        html += '<div class="form-group" id="inputFormRow">';
        html += '<label for="canceled_reason" class="col-sm-3 control-label">Cancel Reason<strong class="text-danger"> *</strong></label>';
        html += '<div class="col-sm-7">';
        html += '<select name="cancel_reason" class="form-control" autocomplete="off" required>';
        html += '<option>Plan Cancel</option>';
        html += '<option>Purchased Online</option>';
        html += '<option>Other Travel Agent</option>';
        html += '<option>Plan Postponed</option>';
        html += '<option>Number NR</option>';
        html += '<option>Expensive</option>';
        html += '<option>Wrong Number</option>';
        html += '<option>Office Location</option>';
        html += '<option>Other</option>';
        html += '</select>';
        html += '</div>';
        html += '</div>';

        $('#newRow').append(html);
        }else if(status_row === 'Confirmed'){
            $('#inputFormRow').closest('#inputFormRow').remove();

        var html = '';
        html += '<div class="form-group" id="inputFormRow2">';
        html += '<label for="confirm_amount" class="col-sm-3 control-label">Confirmed Amount<strong class="text-danger"> *</strong></label>';
        html += '<div class="col-sm-2">';
        html += '<input type="text" id="confirmed_amount" onkeyup="calculated_amount_function()" name="confirmed_amount" class="form-control" placeholder="Enter Confirmed Amount" autocomplete="off" required><br>';
        html += '</div>';
        html += '<label for="confirm_amount" class="col-sm-2 control-label">Calculated Amount<strong class="text-danger"> *</strong></label>';
        html += '<div class="col-sm-3">';
        html += '<input type="text" id="calculated_amount" onkeyup="calculated_amount_function()" name="calculated_amount" class="form-control" placeholder="Enter Calculated Amount" autocomplete="off" required><br>';
        html += '</div>';
        html += '<label for="saved_amount" class="col-sm-3 control-label">Total Saved Amount<strong class="text-danger"></strong></label>';
        html += '<div class="col-sm-7">';
        html += '<h4><badge class="badge badge-success" id="total_amount">0</badge></h4><br>';
        html += '</div>';
        html += '</div>';

        $('#newRow').append(html);
        }
        else{
            $('#inputFormRow').closest('#inputFormRow').remove();
            $('#inputFormRow2').closest('#inputFormRow2').remove();
            $('#inputFormRow3').closest('#inputFormRow3').remove();
        }

    }
    // remove row
    $(document).on('click', '#removeRow', function () {
        $(this).closest('#inputFormRow').remove();
    });
</script>

<script>
    function calculated_amount_function(){
            var confirmed_amount = $('#confirmed_amount').val();
            var calculated_amount = $('#calculated_amount').val();

            var total_saved_amount = confirmed_amount - calculated_amount;

             $('#total_amount').text(total_saved_amount+' /=');

             if(confirmed_amount < calculated_amount){
                 $("#total_amount").removeClass("badge-success");
                 $("#total_amount").addClass("badge-danger");
             }else{
                 $("#total_amount").removeClass("badge-danger");
                 $("#total_amount").addClass("badge-success");
             }

    }

</script>

<script>
$(document).ready(function() {
  $("#progress_remarks").on('keyup', function() {
    var words = 0;

    if ((this.value.match(/\S+/g)) != null) {
      words = this.value.match(/\S+/g).length;
    }

    if (words > 50) {
      // Split the string on first 200 words and rejoin on spaces
      var trimmed = $(this).val().split(/\s+/, 100).join(" ");
      // Add a space at the end to make sure more typing creates new words
      $(this).val(trimmed + " ");
    }
    else {
      $('#display_count').text(words);
      $('#word_left').text(50-words);
    }
  });
});
</script>
<!--<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script>
                                            CKEDITOR.replace( '#editor1' );
</script>-->
@endsection

