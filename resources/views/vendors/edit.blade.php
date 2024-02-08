{{ Form::model($vendor, array('route' => array('vendors.update', $vendor->id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data')) }}

<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $settings = \App\Models\Utility::settings();
    @endphp
    @if($settings['ai_chatgpt_enable'] == 'on')
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['warehouse']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('vendor_name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('vendor_name', null, array('class' => 'form-control','required'=>'required')) }}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        <div class="form-group col-md-12">
            {{Form::label('company_details',__('Company Details'),array('class'=>'form-label')) }}
            {{Form::textarea('company_details',null,array('class'=>'form-control','rows'=>3))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('amount_paid_new',__('Paid'),array('class'=>'form-label')) }}
            {{Form::number('amount_paid_new',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_paid_new'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('amount_due',__('Due'),array('class'=>'form-label')) }}
            {{Form::hidden('original_due', $vendor->amount_due)}}
            {{Form::number('amount_due',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_due', 'readonly' => 'readonly'))}}
        </div>
        <div class="form-group col-md-12">
    {{Form::label('attachment',__('Company Attachment'),array('class'=>'form-label')) }}
    {{Form::file('attachment', array('class'=>'form-control'))}}
</div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Edit')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        // Get the original due amount
        var originalDue = parseFloat($('#amount_due').val());

        // Calculate due amount when paid amount changes
        $('#amount_paid_new').on('input', function() {
            var amountPaid = parseFloat($(this).val());

            if (!isNaN(amountPaid)) {
                // Subtract the paid amount from the original due amount only once
                var newDue = originalDue - amountPaid;
                // Ensure the new due amount is not negative
                newDue = Math.max(0, newDue);
                $('#amount_due').val(newDue.toFixed(2));
            }
        });
    });
</script>
