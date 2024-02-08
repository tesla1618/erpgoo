<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>

<?php

    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
   
    $results = [];
    

    // Get data from the database based on the 'visa_type' parameter

        try {
            // Establish a database connection
            $connection = DB::connection();

            // Escape the user input to prevent SQL injection
        

            // Execute a raw SQL query
            $results = $connection->select("SELECT * FROM vendors");
            
        } catch (\Exception $e) {
            // Log the error message
        }
    
?>

<?php
    $totalAmountPaid = 0;
    $totalAmountDue = 0;
    foreach ($results as $result) {
        $totalAmountPaid += $result->amount_paid;
    }
    foreach ($results as $result) {
        $totalAmountDue += $result->amount_due;
    }
$totalAmountPaidf = '$' . number_format($totalAmountPaid, 2);
$totalAmountDuef = '$' . number_format($totalAmountDue, 2);

?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Vendors')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-7">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-secondary">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2"><?php echo e(__('Total')); ?></p>
                                            <h6 class="mb-3"><?php echo e(__('Vendors')); ?></h6>
                                            <h3 class="mb-0"><?php echo e(count($results)); ?></h3>

                                            </h3>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti ti-report-money"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2"><?php echo e(__('Total')); ?></p>
                                            <h6 class="mb-3"><?php echo e(__('Paid')); ?></h6>
                                            <h3 class="mb-0"><?php echo e($totalAmountPaidf); ?> </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti ti-report-money"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2"><?php echo e(__('Total')); ?></p>
                                            <h6 class="mb-3"><?php echo e(__('Due')); ?></h6>
                                            <h3 class="mb-0"><?php echo e($totalAmountDuef); ?> </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="card">

<div class="card-body table-border-style">

    <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Vendor Name')); ?></th>
                        <th scope="col"><?php echo e(__('Details')); ?></th>
                        <th scope="col"><?php echo e(__('Paid')); ?></th>
                        <th scope="col"><?php echo e(__('Due')); ?></th>
                        <th scope="col"><?php echo e(__('Attachment')); ?></th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($index + 1); ?></th>
                            <td><?php echo e($result->vendor_name); ?></td>
                            <td><?php echo e($result->company_details); ?></td>
                            <td><?php echo e($result->amount_paid); ?></td>
                            <td><?php echo e($result->amount_due); ?></td>
                            <?php if($result->attachment == null): ?> {
                                <td>No Attachment</td>
                            }
                            <?php else: ?>
                                <td><a href="<?php echo e(asset('uploads/' . $result->attachment)); ?>">File</a></td>
                            <?php endif; ?>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            </div>
            </div>
            </div>
                        
                        
                        </div>
                        
                        
                       
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/main-file/resources/views/vendors/dashboard.blade.php ENDPATH**/ ?>