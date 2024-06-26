<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Vendor Ledger')); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<!-- change amount field: unit price*number of unit -->
<script>
    $(document).on('change', 'input[name=unit_pirce], input[name=number_of_unit]', function(){
        var unit_price = $('input[name=unit_pirce]').val();
        var number_of_unit = $('input[name=number_of_unit]').val();
        var amount = unit_price * number_of_unit;
        $('input[name=amount]').val(amount);
    });
</script>
<!-- Calculate due : amount - advance -->

<script>
    $(document).on('change', 'input[name=amount], input[name=advance]', function(){
        var amount = $('input[name=amount]').val();
        var advance = $('input[name=advance]').val();
        var due = amount - advance;
        if (due < 0) {
            
            $('input[name=deposit]').val(-due);
            $('input[name=due]').val(0);
            
        }
        else {
            $('input[name=due]').val(due);
            $('input[name=deposit]').val(0);
        }
    });
</script>
<?php $__env->stopPush(); ?>


<?php
    $vendorId = request()->query('vendor_id');
    $agent_name = \App\Models\Vendor::where('id', $vendorId)->first();
    $agents = \App\Models\Vendor::pluck('vendor_name', 'id');
    $ledgers = \App\Models\LedgerV::where('vendor_id', $vendorId)->get();
?>




<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item active"><a href="/ledger/agent"><?php echo e(__('Vendor Ledger')); ?></a></li>
    <li class="breadcrumb-item active"><?php echo e($agent_name->vendor_name); ?></li>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createAgent">
        <i class="ti ti-plus"></i>
        </button>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="modal fade" id="createAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form method="post" action="<?php echo e(route('ledger.store')); ?>">
  <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Ledger Information</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
                <div class="form-group">
                    <label for="agent_id" class="form-label">Agent</label>
                    <select name="agent_id" class="form-control" required>
                        <option value="" disabled selected>Select Agent</option>
                        <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" required>


                    
                </div>
                <div class="form-group">
                    <label for="paid_for" class="form-label">Paid For</label>
                    <input type="text" name="paid_for" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" name="unit_pirce" class="form-control" required>
</div>
                <div class="form-group
                ">
                    <label for="number_of_unit" class="form-label
                    ">Number of Unit</label>
                    <input type="number" name="number_of_unit" class="form-control" required>
</div>

                
                <div class="form-group">
                    <label for="amount" class="form-label
                    ">Amount</label>
                    <input type="number" name="amount" class="form-control" required>
</div>
<div class="form-group">
                    <label for="payment_mode" class="form-label
                    ">Payment Mode</label>
                    <input type="text" name="payment_mode" class="form-control" required>
</div>


                <div class="form-group">
                    <label for="advance" class="form-label
                    ">Advance</label>
                    <input type="number" name="advance" class="form-control" required>
</div>
                <div class="form-group">
                    <label for="deposit" class="form-label
                    ">Deposit</label>
                    <input type="number" name="deposit" class="form-control" required>
</div>
                    
                    <div class="form-group
                    ">
                        <label for="due" class="form-label
                        ">Due</label>
                        <input type="number" name="due" class="form-control" required>
</div>
                    <div class="form-group
                    ">
                        <label for="refund" class="form-label
                        ">Refund</label>
                        <input type="number" name="refund" class="form-control" value="0" required>
</div>

                
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Ledger"></input>
      </div>
    </div>
    </form>
  </div>
</div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Agent')); ?></th>
                                <th><?php echo e(__('Paid For')); ?></th>
                                <th><?php echo e(__('Unit Price')); ?></th>
                                <th><?php echo e(__('Number of Unit')); ?></th>
                                <th><?php echo e(__('Payment Mode')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                                <th><?php echo e(__('Advance')); ?></th>
                                <th><?php echo e(__('Due')); ?></th>
                                <th><?php echo e(__('Deposit')); ?></th>
                                <th><?php echo e(__('Refund')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $ledgers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ledger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($ledger->date); ?></td>
                                    <!-- If agent name show name, else show N/A -->
                                    <td><?php echo e($ledger->vendor ? $ledger->vendor->vendor_name : 'N/A'); ?></td>
                                    <td><?php echo e($ledger->paid_for); ?></td>
                                    <td><?php echo e($ledger->unit_pirce); ?></td>
                                    <td><?php echo e($ledger->number_of_unit); ?></td>
                                    <td><?php echo e($ledger->payment_mode); ?></td>
                                    <td><?php echo e($ledger->amount); ?></td>
                                    <td><?php echo e($ledger->advance); ?></td>
                                    <td><?php echo e($ledger->due); ?></td>
                                    <td><?php echo e($ledger->deposit); ?></td>
                                    <td><?php echo e($ledger->refund); ?></td>
                                    <td>
                                    <div class="action-btn bg-danger ms-2">
    <?php echo Form::open(['method' => 'DELETE', 'route' => ['ledger.destroy', $ledger->id], 'id' => 'delete-form-'.$ledger->id, 'class' => 'delete-form']); ?>

    <button type="button" class="btn btn-sm align-items-center bs-pass-para delete-btn" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>">
        <i class="ti ti-trash text-white"></i>
    </button>
    <?php echo Form::close(); ?>

</div>

<script>
    // Add event listener to all delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm("Are you sure you want to delete?")) {
                // If confirmed, submit the form
                this.closest('form').submit();
            }
        });
    });
</script>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/vledgerv/index2.blade.php ENDPATH**/ ?>