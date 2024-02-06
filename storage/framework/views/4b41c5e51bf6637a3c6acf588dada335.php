<?php
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
    $vtype = isset($_GET['visa_type']) ? $_GET['visa_type'] : null;
    $aid = isset($_GET['agent']) ? $_GET['agent'] : null;
    $results = [];
    

    // Get data from the database based on the 'visa_type' parameter
    if (!is_null($aid)) {
        try {
            // Establish a database connection
            $connection = DB::connection();

            // Escape the user input to prevent SQL injection
            $aid = $connection->getPdo()->quote($aid);

            // Execute a raw SQL query
            $results = $connection->select("SELECT * FROM clients WHERE agent_id = $aid");
            $agent_name = $connection->select("SELECT agent_name FROM agents WHERE id = $aid");
            $agent_name = $agent_name[0]->agent_name;
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the database operation
            echo "Error: " . $e->getMessage();
        }
    }
?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Agents')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item">
    <?php if(isset($_GET['visa_type'])): ?>
    <?php
        $vtype = $_GET['visa_type'];
    ?>

    <?php if($vtype == "WV"): ?>
        <?php echo e(__('Work Permit Visa')); ?>

    <?php elseif($vtype == "BV"): ?>
        <?php echo e(__('Business Visa')); ?>

    <?php elseif($vtype == "SV"): ?>
        <?php echo e(__('Student Visa')); ?>

    <?php elseif($vtype == "TV"): ?>
        <?php echo e(__('Tourist Visa')); ?>

    <?php elseif($vtype == "OV"): ?>
        <?php echo e(__('Others')); ?>

    <?php else: ?>
        <?php echo e($vtype); ?>

    <?php endif; ?>
<?php endif; ?>
</li>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <!-- <button data-size="md" data-bs-target="#createAgent" title="<?php echo e(__('Create Client')); ?>" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </button> -->
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createAgent">
        <i class="ti ti-plus"></i>
        </button>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="modal fade" id="createAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form method="post" action="<?php echo e(route('agents.store')); ?>">
  <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Agent</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
                <div class="form-group">
                    <label for="agent_name" class="form-label">Agent Name</label>
                    <input type="text" name="agent_name" class="form-control" placeholder="Agent Name" required>
                    <label for="agent_name" class="form-label">Passport Number</label>
                    <input type="text" name="passport_number" class="form-control" placeholder="Agent Passport Number" required>
                </div>
                <div class="form-group">
                    <label for="agent_name" class="form-label">Visa Type</label>
                    <select name="visa_type" class="form-control" required>
                        <option value="WV">Work Permit Visa</option>
                        <option value="BV">Business Visa</option>
                        <option value="SV">Student Visa</option>
                        <option value="TV">Tourist Visa</option>
                        <option value="OV">Others</option>
                    </select>

                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Agent"></input>
      </div>
    </div>
    </form>
  </div>
</div>



<div class="modal" aria-labelledby="createAgent">
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="modal-body">
            <div class="row">
                <div class="form-group">
                    <label for="agent_name">Agent Name</label>
                    <input type="text" name="agent_name" class="form-control" placeholder="Enter Agent Name" required>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <input type="button" value="Cancel" class="btn  btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Create" class="btn  btn-primary">
        </div>
    </form>
</div>

    <div class="row mt-4">
        <div class="col-xxl-12">
            <div class="alert alert-success mb-4">
                Showing all clients for the agent <b><?php echo e($agent_name); ?></b>
            </div>
        <div class="card">

        <div class="card-body table-border-style">


            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Client Name')); ?></th>
                        <th scope="col"><?php echo e(__('Passport Number')); ?></th>
                        <th scope="col"><?php echo e(__('Visa Type')); ?></th>
                        <th scope="col"><?php echo e(__('Paid')); ?></th>
                        <th scope="col"><?php echo e(__('Due')); ?></th>
                        <th scope="col"><?php echo e(__('Status')); ?></th>


                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($index + 1); ?></th>
                            <td><?php echo e($result->client_name); ?></td>
                            <td><?php echo e($result->passport_no); ?></td>
                            <td><?php echo e($result->visa_type); ?></td>
                            <td><?php echo e($result->amount_paid); ?></td>
                            <td><?php echo e($result->amount_due); ?></td>
                            <td><?php echo e($result->status); ?></td>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/main-file/resources/views/agents/clients.blade.php ENDPATH**/ ?>