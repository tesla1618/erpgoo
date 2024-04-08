<?php
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    $countryName = isset($_GET['country']) ? $_GET['country'] : null;
    $countryName2 = isset($_GET['country']) ? $_GET['country'] : null;
    $cresults = [];
    $aresults = [];
    $vresults = [];
    $countries = [];
    

    // Get data from the database based on the 'visa_type' parameter
    
        try {
            // Establish a database connection
            $connection = DB::connection();

            // Escape the user input to prevent SQL injection
            $countryName = $connection->getPdo()->quote($countryName);

            // Execute a raw SQL query
            $cresults = $connection->select("SELECT clients.*,
                countries.country_name FROM clients
                LEFT JOIN countries ON clients.visa_country_id = countries.id
                WHERE countries.country_name = $countryName
            ");

            $aresults = $connection->select("SELECT agents.*,
                countries.country_name FROM agents
                LEFT JOIN countries ON agents.visa_country_id = countries.id
                WHERE countries.country_name = $countryName
            ");

            $vresults = $connection->select("SELECT vendors.*,
                countries.country_name FROM vendors
                LEFT JOIN countries ON vendors.visa_country_id = countries.id
                WHERE countries.country_name = $countryName
            ");

            $countries = $connection->select("SELECT * FROM countries");
            //$results = $connection->select("SELECT * FROM clients");
            
        } catch (\Exception $e) {
            //echo "Error: " . $e->getMessage();
        }
    
?>

<?php $__env->startSection('page-title'); ?>
<?php if(isset($_GET['visa_type'])): ?>
    <?php
        $countryName = $_GET['countryName'];
    ?>
<?php endif; ?>
    <?php echo e($countryName2); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Countries')); ?></li>
    <li class="breadcrumb-item">
    <?php if(isset($_GET['visa_type'])): ?>
    <?php
        $countryName = $_GET['countryName'];
    ?>
<?php endif; ?>
    <?php echo e($countryName2); ?>

</li>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>


    <div class="row mt-4">
        <div class="col-xxl-12">
           
        <div class="card">

        <div class="card-header">
            <h5 class="mt-1 mb-0"><?php echo e(__('Clients')); ?></h5>
        </div>

        <div class="card-body table-border-style">


            <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Client Name')); ?></th>
                        <th scope="col"><?php echo e(__('Passport Number')); ?></th>
                        <th scope="col"><?php echo e(__('Client ID')); ?></th>
                        <th scope="col"><?php echo e(__('Visa Type')); ?></th>
                        <th scope="col"><?php echo e(__('Country')); ?></th>
                        <th scope="col"><?php echo e(__('Paid')); ?></th>
                        <th scope="col"><?php echo e(__('Due')); ?></th>
                        <th scope="col"><?php echo e(__('Status')); ?></th>


                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__currentLoopData = $cresults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($index + 1); ?></th>
                            <td><?php echo e($result->client_name); ?></td>
                            <td><?php echo e($result->passport_no); ?></td>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/countries/clients.blade.php ENDPATH**/ ?>