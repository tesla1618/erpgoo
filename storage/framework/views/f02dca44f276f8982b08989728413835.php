<?php echo e(Form::open(array('route' => 'agents.store'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group">
            <?php echo e(Form::label('agent_name', __('Agent Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('agent_name', null, array('class' => 'form-control','placeholder'=>__('Enter Agent Name'),'required'=>'required'))); ?>

        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>

<?php echo e(Form::close()); ?>

<?php /**PATH /home/tesla/Desktop/ERP/main-file/resources/views/agents/create.blade.php ENDPATH**/ ?>