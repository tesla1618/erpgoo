<?php
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
    $vtype = isset($_GET['visa_type']) ? $_GET['visa_type'] : null;
    $aid = isset($_GET['vendor']) ? $_GET['vendor'] : null;
    $results = [];
    $countries = [];
    

    // Get data from the database based on the 'visa_type' parameter
    
        try {
            // Establish a database connection
            $connection = DB::connection();

            // Escape the user input to prevent SQL injection
            $aid = $connection->getPdo()->quote($aid);
            $vtype = $connection->getPdo()->quote($vtype);

            // Execute a raw SQL query
            //$results = $connection->select("SELECT clients.*, countries.country_name FROM clients LEFT JOIN countries ON clients.visa_country_id = countries.id WHERE clients.vendor_id = $aid");
            $countries = $connection->select("SELECT * FROM countries");
            //$results = $connection->select("SELECT * FROM clients WHERE vendor_id = $aid");
            $agent_name = $connection->select("SELECT vendor_name FROM vendors WHERE id = $aid");
            
        } catch (\Exception $e) {
            // Log the error message
        }
    
?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Countries')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Countries')); ?></li>
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
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createAgent">
        <i class="ti ti-plus"></i>
        </button>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="modal fade" id="createAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <form method="post" action="<?php echo e(route('countries.store')); ?>">
  <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Country</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
                <div class="form-group">
                    <label for="agent_name" class="form-label">Country Name</label>
                    <input type="text" name="country_name" class="form-control" placeholder="Enter a Country Name" required>
                </div>
                

                </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Country"></input>
      </div>
    </div>
    </form>
  </div>
</div>



    <div class="row mt-4">
        <div class="col-xxl-12">
           
        <div class="card">

        <div class="card-body table-border-style">


            <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Country Name')); ?></th>
                        <th scope="col"><?php echo e(__('Agents')); ?></th>
                        <th scope="col"><?php echo e(__('Clients')); ?></th>
                        <th scope="col"><?php echo e(__('Vendors')); ?></th>
                        <th scope="col"><?php echo e(__('Action')); ?></th>
                        


                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($index + 1); ?></th>
                            <td>
                                    
                                <?php echo e($result->country_name); ?>

                           
                        </td>

                        <td>
                            <a href="/countries/agents?country=<?php echo e($result->country_name); ?>">
                                <?php echo e(__('View')); ?>

                            </a>
                        </td>
                        <td>
                            <a href="/countries/clients?country=<?php echo e($result->country_name); ?>">
                                <?php echo e(__('View')); ?>

                            </a>
                        </td>
                        <td>
                            <a href="/countries/vendors?country=<?php echo e($result->country_name); ?>">
                                <?php echo e(__('View')); ?>

                            </a>
                        </td>
                        <td>
                        <div class="action-btn bg-danger ms-2">
    <?php echo Form::open(['method' => 'DELETE', 'route' => ['countries.destroy', $result->id], 'id' => 'delete-form-'.$result->id, 'class' => 'delete-form']); ?>

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/countries/index.blade.php ENDPATH**/ ?>