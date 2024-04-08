@extends('layouts.admin')
@section('page-title')
    {{__('Agents')}}
@endsection

@php
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
    
@endphp

@push('script-page')
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Agents')}}</li>
@endsection
<!-- -->

@section('content')




    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Agent ID')}}</th>
                                <th>{{__('Visa Type')}}</th>
                                <th>{{__('Country')}}</th>
                                <th>{{__('Unit Price')}}</th>
                                <th>{{__('Paid')}}</th>
                                <th>{{__('Due')}}</th>
                                <th>{{__('Refund')}}</th>
                                <th>{{__('Attachment')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($results as $index => $result)
                                <tr class="font-style">
                                    <td>{{ $result->agent_name}}</td>
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
                                    <td>{{ number_format($result->total_unit_price, 2) }}</td>

                                    <td>{{ number_format($result->total_amount_paid - $result->refund, 2) }}</td>
                                    <td>{{ number_format($result->total_amount_due, 2) }}</td>
                                    <td>{{ number_format($result->total_refund, 2) }}</td>
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

                                    @if(Gate::check('show warehouse') || Gate::check('edit warehouse') || Gate::check('delete warehouse'))
                                        <td class="Action">
                                            
                                            @can('edit warehouse')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('agents.edit',$result->id) }}" data-ajax-popup="true"  data-size="lg " data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit ')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('delete warehouse')
                                            <div class="action-btn bg-danger ms-2">
    {!! Form::open(['method' => 'DELETE', 'route' => ['agents.destroy', $result->id], 'id' => 'delete-form-'.$result->id]) !!}
    <button type="submit" class="btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}">
        <i class="ti ti-trash text-white"></i>
    </button>
    {!! Form::close() !!}
</div>

                                            @endcan
                                        </td>
                                    @endif
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
