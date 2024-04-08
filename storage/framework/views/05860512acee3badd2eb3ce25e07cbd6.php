<?php echo e(Form::model($vendor, array('route' => array('vendors.update', $vendor->id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data'))); ?>


<div class="modal-body">
<?php
    // Fetch clients and agents
    $clients = \App\Models\VClient::all();
    $agents = \App\Models\Agent::pluck('agent_name', 'id');
    $vendors = \App\Models\Vendor::pluck('vendor_name', 'id');
    $countries = \App\Models\Country::pluck('country_name', 'id');
    $connection = DB::connection();
    $results = $connection->select("
                SELECT vendors.*, countries.country_name,
                SUM(clients.amount_paid) AS total_amount_paid,
                SUM(clients.amount_due) AS total_amount_due,
                SUM(clients.refund) AS total_refund,
                AVG(clients.unit_price) AS total_unit_price
                FROM vendors
                LEFT JOIN countries ON vendors.visa_country_id = countries.id
                LEFT JOIN clients ON clients.vendor_id = vendors.id
                where vendors.id = {$vendor->id}
                
            ");
?>
    
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
        <div class="form-group col-md-12">
            <?php echo e(Form::label('vendor_name', __('Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('vendor_name', null, array('class' => 'form-control','required'=>'required'))); ?>

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
        <div class="form-group col-md-12">
            <?php echo e(Form::label('company_details',__('Company Details'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::textarea('company_details',null,array('class'=>'form-control','rows'=>3))); ?>

        </div>
       
        
<div>
    <div class="row">
        
        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
        <div class="form-group col-md-6">
            <?php echo e(Form::label('total_amount_paid',__('Total Amount Paid'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('total_amount_paid', $result->total_amount_paid, array('class'=>'form-control', 'step'=>'any', 'id' => 'total_amount_paid', 'readonly'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('total_amount_due',__('Total Amount Due'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('total_amount_due', $result->total_amount_due, array('class'=>'form-control', 'step'=>'any', 'id' => 'total_amount_due', 'readonly'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('total_refund',__('Total Refund'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('total_refund', $result->total_refund, array('class'=>'form-control', 'step'=>'any', 'id' => 'total_refund', 'readonly'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('total_unit_price',__('Total Unit Price'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('total_unit_price', $result->total_unit_price, array('class'=>'form-control', 'step'=>'any', 'id' => 'total_unit_price', 'readonly'))); ?>

        </div>
        
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<div class="form-group col-md-6">
    <div class="form-group">
        <label for="agent_id" class="form-label"><?php echo e(__('Country')); ?></label>
        <select name="visa_country_id" class="form-control" >
            <option value=""><?php echo e(__('Select Country')); ?></option>
            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $countryId => $countryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($countryId); ?>" <?php echo e($vendor->visa_country_id == $countryId ? 'selected' : ''); ?>><?php echo e($countryName); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value=""><?php echo e(__('N/A')); ?></option>
        </select>
    </div>
</div>
<div class="form-group col-md-6">
        <div class="form-group">
        <label for="visa_type" class="form-label"><?php echo e(__('Visa Type')); ?></label>
        <select name="visa_type" class="form-control" required>
            <option value="<?php echo e($vendor->visa_type); ?>" selected disabled><?php echo e($vendor->visa_type == "SV" ?
            __('Student Visa') : ($vendor->visa_type == "TV" ? __('Tourist Visa') : ($vendor->visa_type == "WV" ? __('Work Permit Visa') : ($vendor->visa_type == "BV" ? __('Business Visa') : ($vendor->visa_type == "OV" ? __('Others') : __('Select Visa Type')))))); ?></option>
            <option value="WV"><?php echo e(__('Work Permit Visa')); ?></option>
            <option value="BV"><?php echo e(__('Business Visa')); ?></option>
            <option value="SV"><?php echo e(__('Student Visa')); ?></option>
            <option value="TV"><?php echo e(__('Tourist Visa')); ?></option>
            <option value="OV"><?php echo e(__('Others')); ?></option>
        </select>
    </div>
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
<?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/vendors/edit.blade.php ENDPATH**/ ?>