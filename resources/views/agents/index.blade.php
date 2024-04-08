@extends('layouts.admin')


@php
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
                SELECT agents.*, countries.country_name,
                SUM(clients.amount_paid) AS total_amount_paid,
                SUM(clients.amount_due) AS total_amount_due,
                SUM(clients.refund) AS total_refund,
                AVG(clients.unit_price) AS total_unit_price
                FROM agents
                LEFT JOIN countries ON agents.visa_country_id = countries.id
                LEFT JOIN clients ON clients.agent_id = agents.id
                WHERE agents.visa_type = $vtype
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
            


            $countries = $connection->select("SELECT * FROM countries");
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the database operation
            echo "Error: " . $e->getMessage();
        }
    }
@endphp

@section('page-title')
    {{ __('Agents') }}
@endsection

@push('script-page')
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">
    @if (isset($_GET['visa_type']))
    @php
        $vtype = $_GET['visa_type'];
    @endphp

    @if ($vtype == "WV")
        {{ __('Work Permit Visa') }}
    @elseif ($vtype == "BV")
        {{ __('Business Visa') }}
    @elseif ($vtype == "SV")
        {{ __('Student Visa') }}
    @elseif ($vtype == "TV")
        {{ __('Tourist Visa') }}
    @elseif ($vtype == "OV")
        {{ __('Others') }}
    @else
        {{ $vtype }}
    @endif
@endif
</li>

@endsection

@section('action-btn')
    <div class="float-end">
        <!-- <button data-size="md" data-bs-target="#createAgent" title="{{ __('Create Client') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </button> -->
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createAgent">
        <i class="ti ti-plus"></i>
        </button>
    </div>
@endsection

@section('content')

<div class="modal fade" id="createAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form method="post" action="{{ route('agents.store') }}">
  @csrf
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
                    <!-- <label for="agent_name" class="form-label">Passport Number</label>
                    <input type="text" name="passport_number" class="form-control" placeholder="Agent Passport Number" required> -->
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
                    <select name="visa_country_id" class="form-control" required>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                        @endforeach
                        
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
        <div class="card">

<div class="card-body table-border-style">

    <div class="table-responsive">
            <table class="table datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Agent Name') }}</th>
                        <th scope="col">{{ __('Agent ID') }}</th>
                        <th scope="col">{{ __('Visa Type') }}</th>
                        <th scope="col">{{ __('Country') }}</th>
                        <th scope="col">{{ __('Unit Price') }}</th>
                        <th scope="col">{{ __('Paid') }}</th>
                        <th scope="col">{{ __('Due') }}</th>
                        <th scope="col">{{ __('Refund') }}</th>
                        <th scope="col">{{ __('Attachment') }}</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($results as $index => $result)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td><a href="/agents?visa_type={{$vtype}}&agent={{$result -> id}}">{{ $result->agent_name }}</a></td>
                            <td>{{ $result->unique_code }}</td>
                            <td>
                                @if ($result->visa_type == "WV")
                                    Work Visa
                                @elseif ($result->visa_type == "SV")
                                    Student Visa
                                @elseif ($result->visa_type == "TV")
                                    Tourist Visa
                                @elseif ($result->visa_type == "BV")
                                    Business Visa
                                @else
                                    Other Visa
                                @endif
                            </td>
                            <td>{{ $result->country_name }}</td>
                            <td>{{ $totUnitPrice }}</td>
                            <td>{{ $totPaid }}</td>
                            <td>{{ $totDue }}</td>
                            <td>{{ $totRefund }}</td>
                            <td>
                            @if (!empty($result->attachment) || !empty($result->attachment2) || !empty($result->attachment3))
                                            <a href="{{ asset(Storage::url($result->attachment)) }}" class="text-body" download>
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ asset(Storage::url($result->attachment2)) }}" class="text-body" download>
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <a href="{{ asset(Storage::url($result->attachmen3)) }}" class="text-body" download>
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <i class="fas fa-times"></i>
                                        @endif
                                    </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
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
@endpush
