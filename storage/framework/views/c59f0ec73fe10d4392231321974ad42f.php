<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>



<?php

    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
   
    $agents = [];
    $clients = [];
    $vendors = [];
    $adue = [];
    $apaid = [];
    $vdue = [];
    $vpaid = [];
    $cdue = [];
    $cpaid = [];
    $countries = [];
    $expenses = [];
    $expsum = [];
    $revenue = [];
    $ctotpaid = [];
    $ctotdue = [];

    // Get data from the database based on the 'visa_type' parameter

        try {
            // Establish a database connection
            $connection = DB::connection();

            // Escape the user input to prevent SQL injection
        

            // Execute a raw SQL query

            $cresults = $connection->select("SELECT clients.*,
                countries.country_name FROM clients
                LEFT JOIN countries ON clients.visa_country_id = countries.id
                
            ");

            $aresults = $connection->select("SELECT agents.*,
                countries.country_name FROM agents
                LEFT JOIN countries ON agents.visa_country_id = countries.id
                
            ");

            $vresults = $connection->select("SELECT vendors.*,
                countries.country_name FROM vendors
                LEFT JOIN countries ON vendors.visa_country_id = countries.id
                
            ");

            $agents = $connection->select("SELECT * FROM agents");
            $clients = $connection->select("SELECT * FROM clients");
            $vendors = $connection->select("SELECT * FROM vendors");
            $adue = $connection->select("SELECT SUM(amount_due) as total_due FROM clients WHERE agent_id IS NOT NULL");
            $apaid = $connection->select("SELECT SUM(amount_paid) as total_paid FROM clients WHERE agent_id IS NOT NULL");
            $vdue = $connection->select("SELECT SUM(amount_due) as total_due FROM clients WHERE vendor_id IS NOT NULL");
            $vpaid = $connection->select("SELECT SUM(amount_paid) as total_paid FROM clients WHERE vendor_id IS NOT NULL");
            $cdue = $connection->select("SELECT SUM(amount_due) as total_due FROM clients WHERE agent_id IS NOT NULL");
            $ctotdue = $connection->select("SELECT SUM(amount_due) as total_due FROM clients");
            $cpaid = $connection->select("SELECT SUM(amount_paid) as total_paid FROM clients WHERE agent_id IS NOT NULL");
            $ctotpaid = $connection->select("SELECT SUM(amount_paid) as total_paid FROM clients ");
            $countries = $connection->select("SELECT * FROM countries");
            $expsum = $connection->select("SELECT SUM(expense_amount) as total_expense FROM vexpense");

            $expenses = DB::table('vexpense')
    ->select(DB::raw('SUM(expense_amount) as total_expense, expense_date, expense_name'))
    ->groupBy('expense_date')
    ->get();
    
                
    

        } catch (\Exception $e) {
            // Log the error message
        }
        $categories = [];
$data = [];  

foreach ($expenses as $expense) {
    $categories[] = $expense->expense_name;
    $data[] = $expense->total_expense;
}
?>

<?php
    $adue = $adue[0]->total_due;
    $apaid = $apaid[0]->total_paid;
    $vdue = $vdue[0]->total_due;
    $vpaid = $vpaid[0]->total_paid;
    $cdue = $cdue[0]->total_due;
    $ctotdue = $ctotdue[0]->total_due;
    $cpaid = $cpaid[0]->total_paid;
    $ctotpaid = $ctotpaid[0]->total_paid;
    $expsum = $expsum[0]->total_expense;
    $totPaidAgent = 0;
    $totDueAgent = 0;
    $totPaidVendor = 0;
    $totDueVendor = 0;
    $totPaidClient = 0;
    $totRefundClient = 0;
    $totDueClient = 0;
    $totExp = 0;

    foreach ($agents as $result) {
        $totPaidAgent += $result->amount_paid;
    }

    foreach ($vendors as $result) {
        $totPaidVendor += $result->amount_paid;
    }

    foreach ($clients as $result) {
        $totPaidClient += $result->amount_paid;
    }
    foreach ($clients as $result) {
        $totRefundClient += $result->refund;
    }

    foreach ($agents as $result) {
        $totDueAgent += $result->amount_due;
    }

    foreach ($vendors as $result) {
        $totDueVendor += $result->amount_due;
    }

    foreach ($clients as $result) {
        $totDueClient += $result->amount_due;
    }

  //  foreach ($expenses as $result) {
    //    $totExp += $result->expense_amount;
    //}

    $totDueClient = $totDueClient + $cdue;
    $totPaidClient = $totPaidClient + $cpaid;
    $totPaidClientf = '৳' . number_format($totPaidClient, 2);
    $totDueClientf = '৳' . number_format($totDueClient, 2);

    $totDueAgent = $totDueAgent + $adue;
    $totPaidAgent = $totPaidAgent + $apaid;
    $totPaidAgentf = '৳' . number_format($totPaidAgent, 2);
    $totDueAgentf = '৳' . number_format($totDueAgent, 2);

    $totDueVendor = $totDueVendor + $vdue;
    $totPaidVendor = $totPaidVendor + $vpaid;
    $totPaidVendorf = '৳' . number_format($totPaidVendor, 2);
    $totDueVendorf = '৳' . number_format($totDueVendor, 2);

    $alltotPaid =  $ctotpaid - $totRefundClient;
    $alltotDue =  $ctotdue;
    $alltotPaidf = '৳' . number_format($alltotPaid, 2);
    $alltotDuef = '৳' . number_format($alltotDue, 2);

    $totExp = $expsum;
    $totExpf = '৳' . number_format($totExp, 2);

    $totRev = $alltotPaid + $alltotDue - $totExp;
    $totRevf = '৳' . number_format($totRev, 2);

?>



<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/apexcharts.min.js')); ?>"></script>
    <script>
        var options = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: true,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                },
            },
            series: [{
                name: 'Paid',
                data: [<?php echo e($totPaidAgent); ?>, <?php echo e($totPaidVendor); ?>, <?php echo e($totPaidClient); ?>]
            }, {
                name: 'Due',
                data: [<?php echo e($totDueAgent); ?>, <?php echo e($totDueVendor); ?>, <?php echo e($totDueClient); ?>]
            }],
            xaxis: {
                categories: ['Agent', 'Vendor', 'Client'],
            },
            // yaxis: {
            //     title: {
            //         text: 'Amount'
            //     }
            // },
            // colors: ['#3ec9d6', '#FF3A6E'],

            fill: {
                opacity: 1
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                offsetX: 40
            }
        }

        var chart = new ApexCharts(
            document.querySelector("#chart"),
            options
        );

        chart.render();
    </script>   

<?php $__env->stopPush(); ?>


<?php $__env->startPush('script-page'); ?>
<!--Expense Chart-->
<script src="<?php echo e(asset('assets/js/apexcharts.min.js')); ?>"></script>
<script>
     var options = {
        series: [{
        name: 'Expense',
        data: <?php echo json_encode($data); ?> // Use PHP to encode the data as JSON
    }],
    xaxis: {
        categories: <?php echo json_encode($categories); ?> // Use PHP to encode the categories as JSON
    },
          chart: {
          height: 350,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };


    var chart = new ApexCharts(
        document.querySelector("#expchart"),
        options
    );

    chart.render();
</script>
<?php $__env->stopPush(); ?>




<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Overviews')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-12">
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
                                            <h6 class="mb-3"><?php echo e(__('Agents')); ?></h6>
                                            <h3 class="mb-0"><?php echo e(count($agents)); ?></h3>

                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-success">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2"><?php echo e(__('Total')); ?></p>
                                            <h6 class="mb-3"><?php echo e(__('Clients')); ?></h6>
                                            <h3 class="mb-0"><?php echo e(count($clients)); ?></h3>

                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2"><?php echo e(__('Total')); ?></p>
                                            <h6 class="mb-3"><?php echo e(__('Vendors')); ?></h6>
                                            <h3 class="mb-0"><?php echo e(count($vendors)); ?></h3>

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
                                            <h3 class="mb-0"><?php echo e($alltotPaidf); ?> </h3>
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
                                            <h3 class="mb-0"><?php echo e($alltotDuef); ?> </h3>
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
                                            <h6 class="mb-3"><?php echo e(__('Expenses')); ?></h6>
                                            <h3 class="mb-0"><?php echo e($totExpf); ?> </h3>
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
                                            <h6 class="mb-3"><?php echo e(__('Revenue')); ?></h6>
                                            <h3 class="mb-0"><?php echo e($totRevf); ?> </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="theme-avtar bg-danger">
                                                <i class="ti ti-flag"></i>
                                            </div>
                                            <p class="text-muted text-sm mt-4 mb-2"><?php echo e(__('Total')); ?></p>
                                            <h6 class="mb-3"><?php echo e(__('Countries')); ?></h6>
                                            <h3 class="mb-0"><?php echo e(count($countries)); ?> </h3>
                                        </div>
                                    </div>
                                </div>
                                
                               
                            </div>
                            
                            <div class="">
                                    <div class="card">
                                    <div class="card-header">
                                    <h5 class="mt-1 mb-0"><?php echo e(__('Payment Overview')); ?></h5>
                                </div>
                                        <div class="card-body">
                                            <div id="chart"></div>
                                        </div>
                                    </div>
                                </div>
                            <div class="">
                                    <div class="card">
                                    <div class="card-header">
                                    <h5 class="mt-1 mb-0"><?php echo e(__('Expense Overview')); ?></h5>
                                </div>
                                        <div class="card-body">
                                            <div id="expchart"></div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row mt-4">
        <div class="col-xxl-12">
           
        <div class="card">

        <div class="card-header">
            <h5 class="mt-1 mb-0"><?php echo e(__('Clients')); ?></h5>
        </div>

        <div class="card-body table-border-style">


            <div class="table-responsive">
            <table class="table ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Client Name')); ?></th>
                        <th scope="col"><?php echo e(__('Passport Number')); ?></th>
                        <th scope="col"><?php echo e(__('Client ID')); ?></th>
                        <th scope="col"><?php echo e(__('Visa Type')); ?></th>
                        <th scope="col"><?php echo e(__('Country')); ?></th>
                        <th scope="col"><?php echo e(__('Unit Price')); ?></th>
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
                            <td><?php echo e($result->unit_price); ?></td>
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


    <!-- Agents -->

    <div class="row mt-4">
        <div class="col-xxl-12">
           
        <div class="card">

        <div class="card-header">
            <h5 class="mt-1 mb-0"><?php echo e(__('Agents')); ?></h5>
        </div>

        <div class="card-body table-border-style">


            <div class="table-responsive">
            <table class="table ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Agent Name')); ?></th>
                        <th scope="col"><?php echo e(__('Agent ID')); ?></th>
                        <th scope="col"><?php echo e(__('Visa Type')); ?></th>
                        <th scope="col"><?php echo e(__('Country')); ?></th>
                        <!-- <th scope="col"><?php echo e(__('Unit Price')); ?></th>
                        <th scope="col"><?php echo e(__('Paid')); ?></th>
                        <th scope="col"><?php echo e(__('Due')); ?></th> -->


                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__currentLoopData = $aresults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($index + 1); ?></th>
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
                            <!-- <td><?php echo e($result->unit_price); ?></td>
                            <td><?php echo e($result->amount_paid); ?></td>
                            <td><?php echo e($result->amount_due); ?></td> -->
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            </div>
        </div>
        </div>
        </div>
    </div>


    <!-- Vendors -->

    <div class="row mt-4">
        <div class="col-xxl-12">
           
        <div class="card">

        <div class="card-header">
            <h5 class="mt-1 mb-0"><?php echo e(__('Vendors')); ?></h5>
        </div>

        <div class="card-body table-border-style">


            <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?php echo e(__('Vendor Name')); ?></th>
                        <th scope="col"><?php echo e(__('Company Details')); ?></th>
                        <th scope="col"><?php echo e(__('Vendor ID')); ?></th>
                        <th scope="col"><?php echo e(__('Visa Type')); ?></th>
                        <th scope="col"><?php echo e(__('Country')); ?></th>
                        <!-- <th scope="col"><?php echo e(__('Unit Price')); ?></th>
                        <th scope="col"><?php echo e(__('Paid')); ?></th>
                        <th scope="col"><?php echo e(__('Due')); ?></th> -->


                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php $__currentLoopData = $vresults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($index + 1); ?></th>
                            <td><?php echo e($result->vendor_name); ?></td>
                            <td><?php echo e($result->company_details); ?></td>
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
                            <!-- <td><?php echo e($result->unit_price); ?></td>
                            <td><?php echo e($result->amount_paid); ?></td>
                            <td><?php echo e($result->amount_due); ?></td> -->
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
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/tesla/Desktop/ERP/Test2/main-file/resources/views/dashboard/khandashboard.blade.php ENDPATH**/ ?>