@extends('layouts.admin')


@php
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    // Check if 'visa_type' parameter is set in the URL
    $vtype = isset($_GET['visa_type']) ? $_GET['visa_type'] : null;
    $results = [];
    $countries = [];
    

    // Get data from the database based on the 'visa_type' parameter
    
        try {
            // Establish a database connection

            $connection = DB::connection();
            $vtype = $connection->getPdo()->quote($vtype);

            // Escape the user input to prevent SQL injection

            // Execute a raw SQL query
            $results = $connection->select("
    SELECT clients.*, countries.country_name
    FROM clients
    LEFT JOIN countries ON clients.visa_country_id = countries.id
    WHERE clients.visa_type = $vtype and clients.isTicket = 0 and clients.agent_id is NULL and clients.vendor_id is NULL
");
            $countries = $connection->select("SELECT * FROM countries");
            
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the database operation
            echo "Error: " . $e->getMessage();
        }
    
@endphp

@section('page-title')
    {{ __('Clients') }}
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
  <form method="post" action="{{ route('vclients.store') }}">
  @csrf
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
                    <label for="visa_type" class="form-label">Visa Type</label>
                    <select name="visa_type" class="form-control" required>
                        <option value="WV">Work Permit Visa</option>
                        <option value="BV">Business Visa</option>
                        <option value="SV">Student Visa</option>
                        <option value="TV">Tourist Visa</option>
                        <option value="OV">Others</option>
                    </select>

                    <input type="hidden" name="agent_id" value="">
                    <input type="hidden" name="vendor_id" value="">
                    <input type="hidden" name="isTicket" value="0">

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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Client"></input>
      </div>
    </div>
    </form>
  </div>
</div>


<div class="row mt-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    total entries: {{
                        count($results)
                     }}
                    <div class="table-responsive">
                        <table class="table datatable">
                        <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Client Name') }}</th>
                        <th>{{ __('Passport Number') }}</th>
                        <th>{{ __('Client ID') }}</th>
                        <th>{{ __('Visa Type') }}</th>
                        <th>{{ __('Country') }}</th>
                        <th>{{ __('Unit Price') }}</th>
                        <th>{{ __('Paid') }}</th>
                        <th>{{ __('Due') }}</th>
                        <th>{{ __('Refund') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Attachment') }}</th>


                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $index => $result)
                        <tr class="font-style">
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $result->client_name }}</td>
                            <td>{{ $result->passport_no }}</td>
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
                            <td>{{ $result->unit_price }}</td>
                            <td>{{ $result->amount_paid }}</td>
                            <td>{{ $result->amount_due }}</td>
                            <td>{{ $result->refund }}</td>
                            <td>{{ $result->status }}</td>
                            <td>
                            @if (!empty($result->attachment) || !empty($result->attachment2) || !empty($result->attachmen3) || !empty($result->attachment4))
                                          
                                        @if (!empty($result->attachment)) 

                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Passport" href="{{ asset(Storage::url($result->attachment)) }}" class="text-body" download>
                                                <i class="fas fa-passport"></i>
                                            </a>
                                        
                                        @endif
                                        @if (!empty($result->attachment2)) 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Photo" href="{{ asset(Storage::url($result->attachment2)) }}" class="text-body" download>
                                                <i class="fas fa-file-image"></i>
                                            </a>
                                        
                                        @endif
                                        @if (!empty($result->attachmen3)) 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="PCC" href="{{ asset(Storage::url($result->attachmen3)) }}" class="text-body" download>
                                                <i class="fas fa-file"></i>
                                            </a>
                                        
                                        @endif
                                        @if (!empty($result->attachment4)) 
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Others" href="{{ asset(Storage::url($result->attachment4)) }}" class="text-body" download>
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        
                                        @endif
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
