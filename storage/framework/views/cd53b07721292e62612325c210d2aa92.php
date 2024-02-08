<?php echo e(Form::model($vclient, array('route' => array('vclients.update', $vclient->id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data'))); ?>


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
            <?php echo e(Form::label('amount_paid_new',__('Paid'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('amount_paid_new',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_paid_new'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount_due',__('Due'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::hidden('original_due', $vclient->amount_due)); ?>

            <?php echo e(Form::number('amount_due',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_due', 'readonly' => 'readonly'))); ?>

        </div>
        <div class="form-group col-md-12">
    <?php echo e(Form::label('attachment',__('Attachment'),array('class'=>'form-label'))); ?>

    <?php echo e(Form::file('attachment', array('class'=>'form-control'))); ?>

</div>
        <div class="form-group col-md-6">
        <div class="form-group">
        <label for="visa_type" class="form-label"><?php echo e(__('Visa Type')); ?></label>
        <select name="visa_type" class="form-control" required>
            <option value="WV"><?php echo e(__('Work Permit Visa')); ?></option>
            <option value="BV"><?php echo e(__('Business Visa')); ?></option>
            <option value="SV"><?php echo e(__('Student Visa')); ?></option>
            <option value="TV"><?php echo e(__('Tourist Visa')); ?></option>
            <option value="OV"><?php echo e(__('Others')); ?></option>
        </select>
    </div>
</div>
        <div class="form-group col-md-6">
        <div class="form-group">
        <label for="status" class="form-label"><?php echo e(__('Visa Status')); ?></label>
        <select name="status" class="form-control" required>
            <option value="Submitted"><?php echo e(__('Submitted')); ?></option>
            <option value="Work Permit Recieved"><?php echo e(__('Work Permit Recieved')); ?></option>
            <option value="Applied For Visa"><?php echo e(__('Applied For Visa')); ?></option>
            <option value="Visa Recieved"><?php echo e(__('Visa Recieved')); ?></option>
            <option value="Completed"><?php echo e(__('Completed')); ?></option>
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

    // Function to toggle the readonly attribute based on the due amount
    function toggleDueInputEditable(due) {
        if (due === 0) {
            $('#amount_due').prop('readonly', false); // Make the input editable
        } else {
            $('#amount_due').prop('readonly', true); // Make the input readonly
        }
    }

    // Initial call to toggle the due input based on the original due amount
    toggleDueInputEditable(originalDue);

    // Calculate due amount when paid amount changes
    $('#amount_paid_new').on('input', function() {
        var amountPaid = parseFloat($(this).val());

        if (!isNaN(amountPaid)) {
            // Subtract the paid amount from the original due amount only once
            var newDue = originalDue - amountPaid;
            // Ensure the new due amount is not negative
            newDue = Math.max(0, newDue);
            $('#amount_due').val(newDue.toFixed(2));

            // Toggle the readonly attribute based on the new due amount
            toggleDueInputEditable(newDue);
        }
    });
});

</script>
<?php /**PATH /home/tesla/Desktop/ERP/main-file/resources/views/vclients/edit.blade.php ENDPATH**/ ?>