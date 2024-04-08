<?php
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
    $vtype = isset($_GET['visa_type']) ? $_GET['visa_type'] : null;
    $results = [];
    $countries = [];
    $totPaid = 0;
    $totDue = 0;
    $totRefund = 0;
    $totUnitPrice = 0;
    

    // Get data from the database based on the 'visa_type' parameter
    if (!is_null($vtype)) {
        try {
            // Establish a database connection
            $connection = DB::connection();

            // Escape the user input to prevent SQL injection
            $vtype = $connection->getPdo()->quote($vtype);

            // Execute a raw SQL query
            $results = $connection->select("
    SELECT vendors.*, countries.country_name
    FROM vendors
    LEFT JOIN countries ON vendors.visa_country_id = countries.id
    WHERE vendors.visa_type = $vtype
");
$results = $connection->select("
                SELECT vendors.*, countries.country_name,
                SUM(clients.amount_paid) AS total_amount_paid,
                SUM(clients.amount_due) AS total_amount_due,
                SUM(clients.refund) AS total_refund,
                AVG(clients.unit_price) AS total_unit_price
                FROM vendors
                LEFT JOIN countries ON vendors.visa_country_id = countries.id
                LEFT JOIN clients ON clients.vendor_id = vendors.id
                WHERE vendors.visa_type = $vtype
                GROUP BY vendors.id
            ");

            // Get the total paid amount
            if (!empty($results)) {
                $totPaid = $results[0]->total_amount_paid;
                $totRefund = $results[0]->total_refund;
                $totPaid = $totPaid - $totRefund;
                $totPaid = number_format($totPaid, 2);
                $totDue = number_format($results[0]->total_amount_due, 2);
                $totRefund = number_format($results[0]->total_refund, 2);
                $totUnitPrice = number_format($results[0]->total_unit_price, 2);

            }
            $countries = $connection->select("SELECT * FROM countries");
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the database operation
            echo "Error: " . $e->getMessage();
        }
    }
?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Vendors')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Service Providing')); ?></li>
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
  <form method="post" action="<?php echo e(route('vendors.store')); ?>">
  <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Vendor</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
                <div class="form-group">
                    <label for="vendor_name" class="form-label">Vendor Name</label>
                    <input type="text" name="vendor_name" class="form-control" placeholder="Vendor Name" required>
                    <label for="company_details" class="form-label">Company Details</label>
                    <textarea name="company_details" class="form-control" placeholder="Company Details" required></textarea>
                </div>
                <div class="form-group">
                    <label for="visa_type" class="form-label">Visa Type</label>
                    <select name="visa_type" class="form-control" required>
                        <option value="WV">Work Permit Visa</option>
                        <option value="BV">Business Visa</option>
                        <option value="SV">Student Visa</option>
                        <option value="TV">Tourist Visa</option>
                        <option value="OV">Others</option>
                    </select>

                </div>

                <div class="form-group">
                    <label for="country" class="form-label">Visa Country</label>
                    <select name="visa_country_id" class="form-control">
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($country->id); ?>"><?php echo e($country->country_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </select>

                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Vendor"></input>
      </div>
    </div>
    </form>
  </div>
</div>


<div class="row mt-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Vendor ID')); ?></th>
                                <th><?php echo e(__('Company Details')); ?></th>
                                <th><?php echo e(__('Visa Type')); ?></th>
                                <th><?php echo e(__('Country')); ?></th>
                                <th><?php echo e(__('Unit Price')); ?></th>
                                <th><?php echo e(__('Paid')); ?></th>
                                <th><?php echo e(__('Due')); ?></th>
                                <th><?php echo e(__('Refund')); ?></th>
                                <th><?php echo e(__('Attachment')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="font-style">
                                    <td><a href="/vendors?visa_type=<?php echo e($vtype); ?>&vendor=<?php echo e($result -> id); ?>"><?php echo e($result->vendor_name); ?></a></td>
                                    <td><?php echo e($result->unique_code); ?></td>
                                    <td><?php echo e($result->company_details); ?></td>
                                    <td>
                                <?php if($result->visa_type == "WV"): ?>
                                    Work Visa
                                <?php elseif($result->visa_type == "SV"): ?>
                                    Student Visa
                                <?php elseif($result->visa_type == "TV"): ?>
                                    Tourist Visa
                                <?php elseif($result->visa_type == "BV"): ?>
                                    Business Visa
                                <?php else: ?>
                                    Other Visa
                                <?php endif; ?>
                            </td>
                                    <td><?php echo e($result->country_name); ?></td>
                                    <td><?php echo e($totUnitPrice); ?></td>
                                    <td><?php echo e($totPaid); ?></td>
                                    <td><?php echo e($totDue); ?></td>
                                    <td><?php echo e($totRefund); ?></td>
                                    <td>
                            <?php if(!empty($result->attachment) || !empty($result->attachment2) || !empty($result->attachmen3) || !empty($result->attachment4)): ?>
                                          
                                        <?php if(!empty($result->attachment)): ?> 

                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Passport" href="<?php echo e(asset(Storage::url($result->attachment))); ?>" class="text-body" download>
                                                <i class="fas fa-passport"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php if(!empty($result->attachment2)): ?> 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Photo" href="<?php echo e(asset(Storage::url($result->attachment2))); ?>" class="text-body" download>
                                                <i class="fas fa-file-image"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php if(!empty($result->attachmen3)): ?> 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="PCC" href="<?php echo e(asset(Storage::url($result->attachmen3))); ?>" class="text-body" download>
                                                <i class="fas fa-file"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php if(!empty($result->attachment4)): ?> 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Others" href="<?php echo e(asset(Storage::url($result->attachment4))); ?>" class="text-body" download>
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-times"></i>
                                        <?php endif; ?>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/vendors/index.blade.php ENDPATH**/ ?>