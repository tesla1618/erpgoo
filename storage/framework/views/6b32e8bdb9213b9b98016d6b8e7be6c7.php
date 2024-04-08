<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Agents')); ?>

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
            $results = $connection->select("
                SELECT agents.*, countries.country_name,
                SUM(clients.amount_paid) AS total_amount_paid,
                SUM(clients.amount_due) AS total_amount_due,
                SUM(clients.refund) AS total_refund,
                AVG(clients.unit_price) AS total_unit_price
                FROM agents
                LEFT JOIN countries ON agents.visa_country_id = countries.id
                LEFT JOIN clients ON clients.agent_id = agents.id
                
                GROUP BY agents.id
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
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the database operation
            echo "Error: " . $e->getMessage();
        }
    
?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Agents')); ?></li>
<?php $__env->stopSection(); ?>
<!-- -->

<?php $__env->startSection('content'); ?>




    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Agent ID')); ?></th>
                                <th><?php echo e(__('Visa Type')); ?></th>
                                <th><?php echo e(__('Country')); ?></th>
                                <th><?php echo e(__('Unit Price')); ?></th>
                                <th><?php echo e(__('Paid')); ?></th>
                                <th><?php echo e(__('Due')); ?></th>
                                <th><?php echo e(__('Refund')); ?></th>
                                <th><?php echo e(__('Attachment')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="font-style">
                                    <td><?php echo e($result->agent_name); ?></td>
                                    <td><?php echo e($result->unique_code); ?></td>
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
                                    <td><?php echo e(number_format($result->total_unit_price, 2)); ?></td>

                                    <td><?php echo e(number_format($result->total_amount_paid - $result->refund, 2)); ?></td>
                                    <td><?php echo e(number_format($result->total_amount_due, 2)); ?></td>
                                    <td><?php echo e(number_format($result->total_refund, 2)); ?></td>
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

                                    <?php if(Gate::check('show warehouse') || Gate::check('edit warehouse') || Gate::check('delete warehouse')): ?>
                                        <td class="Action">
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit warehouse')): ?>
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('agents.edit',$result->id)); ?>" data-ajax-popup="true"  data-size="lg " data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>"  data-title="<?php echo e(__('Edit ')); ?>">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete warehouse')): ?>
                                            <div class="action-btn bg-danger ms-2">
    <?php echo Form::open(['method' => 'DELETE', 'route' => ['agents.destroy', $result->id], 'id' => 'delete-form-'.$result->id]); ?>

    <button type="submit" class="btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>">
        <i class="ti ti-trash text-white"></i>
    </button>
    <?php echo Form::close(); ?>

</div>

                                            <?php endif; ?>
                                        </td>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/agents/pos.blade.php ENDPATH**/ ?>