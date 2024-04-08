<?php
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
    $vtype = isset($_GET['category']) ? $_GET['category'] : null;
    $results = [];
    $categories = [];
    $totalExp = [];
    $totalExpf = 0;
    

    // Get data from the database based on the 'visa_type' parameter
    
        try {
            // Establish a database connection

            $connection = DB::connection();
            $vtype = $connection->getPdo()->quote($vtype);

            // Escape the user input to prevent SQL injection

            // Execute a raw SQL query
            //$results = $connection->select("SELECT * FROM vexpense");
            $results = $connection->select("
    SELECT vexpense.*, vexcat.category_name
    FROM vexpense
    LEFT JOIN vexcat ON vexpense.vexcat_id = vexcat.id
    WHERE vexcat.category_name = $vtype
");
            $totalExp = $connection->select("SELECT SUM(expense_amount) as total_expense FROM vexpense WHERE vexcat_id = $vtype");
            $categories = $connection->select("SELECT * FROM vexcat");
            
        } catch (\Exception $e) {
            // Log the error message
        }


        $totalAmountPaid = 0;
    foreach ($results as $result) {
        $totalAmountPaid += $result->expense_amount;
    }
$totalAmountPaidf = '৳' . number_format($totalAmountPaid, 2);




?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Expenses')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item">
    Expenses
</li>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>





<div class="row mt-1">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                <form method="post" action="<?php echo e(route('vexpense.store')); ?>">
  <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-body">
      <div class="row">
        <div class="row">

            <div class="form-group col-md-4">
                <label for="vexcat_id" class="form-label">Select Category</label>
                <select name="vexcat_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->category_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

            </div>
            <div class="form-group col-md-4">
                <label for="agent_name" class="form-label">Title</label>
                <input type="text" name="expense_name" class="form-control" placeholder="Expense Title" required>
            </div>
                <div class="form-group col-md-4">
                <label for="passport_no" class="form-label">Amount</label>
                <input type="number" name="expense_amount" class="form-control" placeholder="Expense Amount" required>
            </div>
        </div>

        <div class="row">

            <div class="form-group">
                <label for="expensedate">
                    <?php echo e(__('Date')); ?>

                </label>
                <input class="form-control date" type="date" id="expense_date" name="expense_date" required>
            </div>

                </div>
                <div class="row">
                <div class="form-group col-md-6">
                    <label for="desc" class="form-label">Description</label>
                    <textarea name="expense_type" class="form-control" placeholder="Expense Description"></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="desc" class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" placeholder="Remarks (If any)"></textarea>
                </div>

                </div>
            </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <input type="submit" class="btn btn-primary" value="Add"></input>
      </div>
    </div>
    </form>
                </div>
            </div>
        </div>
    </div>

<div class="row mt-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                <h5 class="mt-1 mb-0">Total Expense: <b class="text-danger"><?php echo e($totalAmountPaidf); ?></b></h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                        <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo e(__('Title')); ?></th>
                        <th><?php echo e(__('Category')); ?></th>
                        <th><?php echo e(__('Description')); ?></th>
                        <th><?php echo e(__('Date')); ?></th>
                        <th><?php echo e(__('Amount')); ?></th>
                        <th><?php echo e(__('Remarks')); ?></th>
                        <th><?php echo e(__('Action')); ?></th>
                        


                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="font-style">
                            <th scope="row"><?php echo e($index + 1); ?></th>
                            <td><?php echo e($result->expense_name); ?></td>
                            <td><?php echo e($result->category_name); ?></td>
                            <td><?php echo e($result->expense_type); ?></td>
                            <td>
                                <?php echo e($result->expense_date); ?>

                            </td>
                            <td><?php echo e($result->expense_amount); ?></td>
                            <td><?php echo e($result->remarks); ?></td>
                            <td>
                            <div class="action-btn bg-danger ms-2">
    <?php echo Form::open(['method' => 'DELETE', 'route' => ['vexpense.destroy', $result->id], 'id' => 'delete-form-'.$result->id]); ?>

    <button type="submit" class="btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>">
        <i class="ti ti-trash text-white"></i>
    </button>
    <?php echo Form::close(); ?>

</div>
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

<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr('required', true);
            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr('required');
            }
        });

        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/vexpenses/index2.blade.php ENDPATH**/ ?>