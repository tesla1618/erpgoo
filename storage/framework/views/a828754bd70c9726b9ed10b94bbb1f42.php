<?php echo e(Form::model($vendor, array('route' => array('vendors.update', $vendor->id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data'))); ?>


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
        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount_paid_new',__('Paid'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('amount_paid_new',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_paid_new'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('amount_due',__('Due'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::hidden('original_due', $vendor->amount_due)); ?>

            <?php echo e(Form::number('amount_due',null,array('class'=>'form-control', 'step'=>'any', 'id' => 'amount_due', 'readonly' => 'readonly'))); ?>

        </div>
        <div class="form-group col-md-12">
    <?php echo e(Form::label('attachment',__('Company Attachment'),array('class'=>'form-label'))); ?>

    <?php echo e(Form::file('attachment', array('class'=>'form-control'))); ?>

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
<?php /**PATH /home/tesla/Desktop/ERP/main-file/resources/views/vendors/edit.blade.php ENDPATH**/ ?>