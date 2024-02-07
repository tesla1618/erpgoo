<?php echo e(Form::open(array('url'=>'award','method'=>'post'))); ?>

<div class="modal-body">
    
    <?php
        $settings = \App\Models\Utility::settings();
    ?>
    <?php if($settings['ai_chatgpt_enable'] == 'on'): ?>
        <div class="text-end">
            <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="<?php echo e(route('generate',['award'])); ?>"
               data-bs-placement="top" data-title="<?php echo e(__('Generate content with AI')); ?>">
                <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
            </a>
        </div>
    <?php endif; ?>
    

    <div class="row">
        <div class="form-group col-md-6 col-lg-6 ">
            <?php echo e(Form::label('employee_id', __('Employee'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('employee_id', $employees,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('award_type', __('Award Type'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('award_type', $awardtypes,null, array('class' => 'form-control select','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('date',__('Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('date',null,array('class'=>'form-control'))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('gift',__('Gift'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('gift',null,array('class'=>'form-control','placeholder'=>__('Enter Gift')))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('description',__('Description'))); ?>

            <?php echo e(Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Description')))); ?>

        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>

<?php /**PATH /home/tesla/Desktop/ERP/main-file/resources/views/award/create.blade.php ENDPATH**/ ?>