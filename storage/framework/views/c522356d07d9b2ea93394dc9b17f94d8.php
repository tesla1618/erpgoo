<?php echo e(Form::model($vclient, array('route' => array('vclients.update', $vclient->id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data'))); ?>


<?php
    // Fetch clients and agents
    $clients = \App\Models\VClient::all();
    $agents = \App\Models\Agent::pluck('agent_name', 'id');
    $vendors = \App\Models\Vendor::pluck('vendor_name', 'id');
    $countries = \App\Models\Country::pluck('country_name', 'id');
?>

<div class="modal-body">
    
    <?php
        $settings = \App\Models\Utility::settings();
    ?>
    <?php if($settings['ai_chatgpt_enable'] == 'on'): ?>
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['warehouse'])); ?>"
           data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
            <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('client_name', __('Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('client_name', null, array('class' => 'form-control','required'=>'required'))); ?>

            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <small class="invalid-name" role="alert">
                <strong class="text-danger"><?php echo e($message); ?></strong>
            </small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('passport_no', __('Passport Number'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('passport_no', null, array('class' => 'form-control','required'=>'required'))); ?>

        </div>


        <div class="form-group col-md-6">
            <?php echo e(Form::label('unit_price',__('Unit Price'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('unit_price',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'unit_price'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('refund',__('Refund'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('refund',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'refund', 'max' => $vclient->amount_paid, 'min' => 0))); ?>

            <div class="alert alert-info mt-2">
                <p><?php echo e(__('Amount Paid')); ?>: <b><?php echo e($vclient->amount_paid); ?></b></p>
            </div>
        </div>
        


        <div class="form-group col-md-4">
            <?php echo e(Form::label('amount_paid_new',__('Paid'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('amount_paid_new',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_paid_new'))); ?>

            <?php echo e(Form::hidden('original_paid', $vclient->amount_paid, array('id' => 'original_paid'))); ?>

        </div>
        <div class="form-group col-md-4">
            <?php echo e(Form::label('amount_due',__('Due'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::hidden('original_due', $vclient->amount_due)); ?>

            <?php echo e(Form::number('amount_due',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_due'))); ?>

        </div>
        <div class="form-group col-md-4">
        <div class="form-group">
        <label for="isTicket" class="form-label"><?php echo e(__('Ticket')); ?></label>
        <select name="isTicket" class="form-control">
    <option value="<?php echo e($vclient->isTicket); ?>"><?php echo e($vclient->isTicket == 1 ? __('Yes') : __('No')); ?></option>
    <option value="<?php echo e($vclient->isTicket == 1 ? 0 : 1); ?>">
        <?php echo e($vclient->isTicket == 1 ? __('No') : __('Yes')); ?>

    </option>
</select>

</div>
        </div>
        
        <div class="form-group col-md-12">
    <?php echo e(Form::label('attachment',__('Passport'),array('class'=>'form-label'))); ?>

    <?php echo e(Form::file('attachment', array('class'=>'form-control'))); ?>

</div>
        <div class="form-group col-md-12">
    <?php echo e(Form::label('attachment2',__('Photo'),array('class'=>'form-label'))); ?>

    <?php echo e(Form::file('attachment2', array('class'=>'form-control'))); ?>

</div>
        <div class="form-group col-md-12">
    <?php echo e(Form::label('attachmen3',__('PCC'),array('class'=>'form-label'))); ?>

    <?php echo e(Form::file('attachmen3', array('class'=>'form-control'))); ?>

</div>
        <div class="form-group col-md-12">
    <?php echo e(Form::label('attachment4',__('Others'),array('class'=>'form-label'))); ?>

    <?php echo e(Form::file('attachment4', array('class'=>'form-control'))); ?>

</div>


<div class="form-group col-md-12">
    <div class="form-group">
        <label for="agent_id" class="form-label"><?php echo e(__('Country')); ?></label>
        <select name="agent_id" class="form-control" >
            <option value=""><?php echo e(__('Select Country')); ?></option>
            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $countryId => $countryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($countryId); ?>" <?php echo e($vclient->visa_country_id == $countryId ? 'selected' : ''); ?>><?php echo e($countryName); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value=""><?php echo e(__('N/A')); ?></option>
        </select>
    </div>
</div>

        <div class="form-group col-md-3">
        <div class="form-group">
        <label for="visa_type" class="form-label"><?php echo e(__('Visa Type')); ?></label>
        <select name="visa_type" class="form-control" required>
            <option value="<?php echo e($vclient->visa_type); ?>" selected disabled><?php echo e($vclient->visa_type == "SV" ?
            __('Student Visa') : ($vclient->visa_type == "TV" ? __('Tourist Visa') : ($vclient->visa_type == "WV" ? __('Work Permit Visa') : ($vclient->visa_type == "BV" ? __('Business Visa') : ($vclient->visa_type == "OV" ? __('Others') : __('Select Visa Type')))))); ?></option>
            <option value="WV"><?php echo e(__('Work Permit Visa')); ?></option>
            <option value="BV"><?php echo e(__('Business Visa')); ?></option>
            <option value="SV"><?php echo e(__('Student Visa')); ?></option>
            <option value="TV"><?php echo e(__('Tourist Visa')); ?></option>
            <option value="OV"><?php echo e(__('Others')); ?></option>
        </select>
    </div>
</div>
        <div class="form-group col-md-3">
        <div class="form-group">
        <label for="status" class="form-label"><?php echo e(__('Visa Status')); ?></label>
        <select name="status" class="form-control" required>
            <option value="Submitted"><?php echo e(__('Submitted')); ?></option>
            <option value="Work Permit Received"><?php echo e(__('Work Permit Received')); ?></option>
            <option value="Applied For Visa"><?php echo e(__('Applied For Visa')); ?></option>
            <option value="Visa Received"><?php echo e(__('Visa Received')); ?></option>
            <option value="Completed"><?php echo e(__('Completed')); ?></option>
            <option value="File Received"><?php echo e(__('File Received')); ?></option>
            <option value="Cancelled"><?php echo e(__('Cancelled')); ?></option>
        </select>
</div>




    </div>

    <div class="form-group col-md-3">
    <div class="form-group">
        <label for="agent_id" class="form-label"><?php echo e(__('Agent')); ?></label>
        <select name="agent_id" class="form-control" >
            <option value=""><?php echo e(__('Select Agent')); ?></option>
            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agentId => $agentName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($agentId); ?>" <?php echo e($vclient->agent_id == $agentId ? 'selected' : ''); ?>><?php echo e($agentName); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value=""><?php echo e(__('N/A')); ?></option>
        </select>
    </div>
</div>
    <div class="form-group col-md-3">
    <div class="form-group">
        <label for="vendor_id" class="form-label"><?php echo e(__('Vendor')); ?></label>
        <select name="vendor_id" class="form-control" >
            <option value=""><?php echo e(__('Select Vendor')); ?></option>
            <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendorId => $vendorName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($vendorId); ?>" <?php echo e($vclient->vendor_id == $vendorId ? 'selected' : ''); ?>><?php echo e($vendorName); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <option value=""><?php echo e(__('N/A')); ?></option>
        </select>
    </div>
</div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Edit')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
    $(document).ready(function() {
    // Get the original due amount
    var originalDue = parseFloat($('#amount_due').val());
    var originalPaid = parseFloat($('#original_paid').val());
    console.log(originalPaid);
    

    // Function to toggle the readonly attribute based on the due amount
    function toggleDueInputEditable(due) {
        if (due === 0) {
            $('#amount_due').prop('readonly', false); // Make the input editable
        } else {
            $('#amount_due').prop('readonly', false); // Make the input readonly
        }
    }

    // Initial call to toggle the due input based on the original due amount
    toggleDueInputEditable(originalDue);

    // Calculate due amount when paid amount changes
    $('#amount_paid_new').on('input', function() {
        var amountPaid = parseFloat($(this).val());
        var unitPrice = parseFloat($('#unit_price').val());

        if (!isNaN(unitPrice) && !isNaN(amountPaid)) {
            // Subtract the paid amount from the original due amount only once
            var newDue = unitPrice - amountPaid - originalPaid;
            // Ensure the new due amount is not negative
            newDue = Math.max(0, newDue);
            $('#amount_due').val(newDue.toFixed(2));

            // Toggle the readonly attribute based on the new due amount
            toggleDueInputEditable(newDue);
        }

        // if (!isNaN(amountPaid)) {
        //     // Subtract the paid amount from the original due amount only once
        //     var newDue = originalDue - amountPaid;
        //     // Ensure the new due amount is not negative
        //     newDue = Math.max(0, newDue);
        //     $('#amount_due').val(newDue.toFixed(2));

        //     // Toggle the readonly attribute based on the new due amount
        //     toggleDueInputEditable(newDue);
        // }
    });
});

</script>
<?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/vclients/edit.blade.php ENDPATH**/ ?>