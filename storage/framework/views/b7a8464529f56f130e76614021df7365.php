<?php
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
   
    $results = [];
    $vendors = [];
    $countries = [];
    

    // Get data from the database based on the 'visa_type' parameter

        try {
            // Establish a database connection
            $connection = DB::connection();

            // Escape the user input to prevent SQL injection
        

            // Execute a raw SQL query
            $results = $connection->select("
    SELECT clients.*, countries.country_name
    FROM clients
    LEFT JOIN countries ON clients.visa_country_id = countries.id
    WHERE clients.isTicket = 1
");
$vendors = $connection->select("SELECT * from vendors");
$countries = $connection->select("SELECT * from countries");
            //$results = $connection->select("SELECT * FROM clients");
            
            
        } catch (\Exception $e) {
            // Log the error message
        }
    
?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Vendors')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Ticket')); ?></li>
    

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
  <form method="post" action="<?php echo e(route('vclients.store')); ?>">
  <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Client</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
                <div class="form-group">
                    <label for="agent_name" class="form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-control" placeholder="Client Name" required>
                    <label for="passport_no" class="form-label">Passport Number</label>
                    <input type="text" name="passport_no" class="form-control" placeholder="Client Passport Number" required>
                </div>
                <div class="form-group">
                    
                    <input type="hidden" name="isTicket" value="1">
                    <input type="hidden" name="agent_id" value="">
                    

                    <label for="visa_type" class="form-label">Visa Type</label>
                    <select name="visa_type" class="form-control" required>
                        <option value="WV">Work Permit Visa</option>
                        <option value="BV">Business Visa</option>
                        <option value="SV">Student Visa</option>
                        <option value="TV">Tourist Visa</option>
                        <option value="OV">Others</option>
                    </select>

                    <label for="vendor_id" class="form-label">Vendor</label>
                    <select name="vendor_id" class="form-control" required>
                        <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agent->id); ?>"><?php echo e($agent->vendor_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <div class="form-group">
                    <label for="country" class="form-label">Visa Country</label>
                    <select name="visa_country_id" class="form-control" required>
                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($country->id); ?>"><?php echo e($country->country_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </select>

                </div>

                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Client"></input>
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
           
        <div class="card">

        <div class="card-body table-border-style">


            <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Client Name')); ?></th>
                        <th scope="col"><?php echo e(__('Client ID')); ?></th>
                        <th scope="col"><?php echo e(__('Passport Number')); ?></th>
                        <th scope="col"><?php echo e(__('Visa Type')); ?></th>
                        <th scope="col"><?php echo e(__('Country')); ?></th>
                        <th scope="col"><?php echo e(__('Unit Price')); ?></th>
                        <th scope="col"><?php echo e(__('Paid')); ?></th>
                        <th scope="col"><?php echo e(__('Due')); ?></th>
                        <th scope="col"><?php echo e(__('Refund')); ?></th>
                        <th scope="col"><?php echo e(__('Attachment')); ?></th>
                        <th scope="col"><?php echo e(__('Status')); ?></th>
                        <th scope="col"><?php echo e(__('Ticket')); ?></th>


                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($index + 1); ?></th>
                            <td><?php echo e($result->client_name); ?></td>
                            <td><?php echo e($result->unique_code); ?></td>
                            <td><?php echo e($result->passport_no); ?></td>
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
                            <td><?php echo e($result->unit_price); ?></td>
                            <td><?php echo e($result->amount_paid); ?></td>
                            <td><?php echo e($result->amount_due); ?></td>
                            <td><?php echo e($result->refund); ?></td>
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
                            <td><?php echo e($result->status); ?></td>
                            <td><?php if($result->isTicket == 1): ?>
                                    Yes
                                <?php else: ?>
                                    No
                                <?php endif; ?></td>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/vendors/ticket.blade.php ENDPATH**/ ?>