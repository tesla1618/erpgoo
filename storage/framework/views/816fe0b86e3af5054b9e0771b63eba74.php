<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Clients')); ?>

<?php $__env->stopSection(); ?>

<?php
    // Fetch clients and agent names
    //$clients = DB::table('clients')->get();
    $connection = DB::connection();

    $results = $connection->select("
    SELECT clients.*, countries.country_name
    FROM clients
    LEFT JOIN countries ON clients.visa_country_id = countries.id
");
    $agents = DB::table('agents')->pluck('agent_name', 'id');
    $vendors = DB::table('vendors')->pluck('vendor_name', 'id');
?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Clients')); ?></li>
<?php $__env->stopSection(); ?>

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
                                    <th><?php echo e(__('Passport Number')); ?></th>
                                    <th><?php echo e(__('Client ID')); ?></th>
                                    <th><?php echo e(__('Visa Type')); ?></th>
                                    <th><?php echo e(__('Country')); ?></th>
                                    <th><?php echo e(__('Unit Price')); ?></th>
                                    <th><?php echo e(__('Paid')); ?></th>
                                    <th><?php echo e(__('Due')); ?></th>
                                    <th><?php echo e(__('Refund')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Attachment')); ?></th>
                                    <th><?php echo e(__('Agent')); ?></th>
                                    <th><?php echo e(__('Vendor')); ?></th>
                                    <th><?php echo e(__('Ticket')); ?></th>
                                    <th><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="font-style">
                                        <td><?php echo e($client->client_name); ?></td>
                                        <td><?php echo e($client->passport_no); ?></td>
                                        <td><?php echo e($client->unique_code); ?></td>
                                        <td>
                                            <?php if($client->visa_type == "WV"): ?>
                                                Work Permit Visa
                                            <?php elseif($client->visa_type == "SV"): ?>
                                                Student Visa
                                            <?php elseif($client->visa_type == "TV"): ?>
                                                Tourist Visa
                                            <?php elseif($client->visa_type == "BV"): ?>
                                                Business Visa
                                            <?php else: ?>
                                                Other Visa
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($client->country_name); ?></td>
                                        <td><?php echo e($client->unit_price); ?></td>
                                        
                                        <td><?php echo e($client->amount_paid - $client->refund); ?></td>
                                        <td><?php echo e($client->amount_due); ?></td>

                                        <td><?php echo e($client->refund); ?></td>
                                        <td><?php echo e($client->status); ?></td>
                                        <td>
                            <?php if(!empty($client->attachment) || !empty($client->attachment2) || !empty($client->attachmen3) || !empty($client->attachment4)): ?>
                                          
                                        <?php if(!empty($client->attachment)): ?> 

                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Passport" href="<?php echo e(asset(Storage::url($client->attachment))); ?>" class="text-body" download>
                                                <i class="fas fa-passport"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php if(!empty($client->attachment2)): ?> 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Photo" href="<?php echo e(asset(Storage::url($client->attachment2))); ?>" class="text-body" download>
                                                <i class="fas fa-file-image"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php if(!empty($client->attachmen3)): ?> 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="PCC" href="<?php echo e(asset(Storage::url($client->attachmen3))); ?>" class="text-body" download>
                                                <i class="fas fa-file"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php if(!empty($client->attachment4)): ?> 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Others" href="<?php echo e(asset(Storage::url($client->attachment4))); ?>" class="text-body" download>
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        
                                        <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-times"></i>
                                        <?php endif; ?>
                                    </td>
                                        <td><?php echo e($agents[$client->agent_id] ?? 'N/A'); ?></td>
                                        <td><?php echo e($vendors[$client->vendor_id] ?? 'N/A'); ?></td>

                                        <td>
                                            <?php if($client->isTicket == 0): ?>
                                            <i class="fas fa-times"></i>
                                            <?php else: ?>
                                            <i class="fas fa-check"></i>

                                            <?php endif; ?>
                                        </td>
                                      
                                        <?php if(Gate::check('show warehouse') || Gate::check('edit warehouse') || Gate::check('delete warehouse')): ?>
                                            <td class="Action">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit warehouse')): ?>
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('vclients.edit',$client->id)); ?>" data-ajax-popup="true"  data-size="lg " data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>"  data-title="<?php echo e(__('Edit ')); ?>">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete warehouse')): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['vclients.destroy', $client->id], 'id' => 'delete-form-'.$client->id]); ?>

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/vclients/pos.blade.php ENDPATH**/ ?>