@extends('layouts.admin')


@section('page-title')
    {{__('Agent Ledger')}}

@endsection

@push('script-page')
<!-- change amount field: unit price*number of unit -->
<script>
    $(document).on('change', 'input[name=unit_pirce], input[name=number_of_unit]', function(){
        var unit_price = $('input[name=unit_pirce]').val();
        var number_of_unit = $('input[name=number_of_unit]').val();
        var amount = unit_price * number_of_unit;
        $('input[name=amount]').val(amount);
    });
</script>
<!-- Calculate due : amount - advance -->

<script>
    $(document).on('change', 'input[name=amount], input[name=advance]', function(){
        var amount = $('input[name=amount]').val();
        var advance = $('input[name=advance]').val();
        var due = amount - advance;
        if (due < 0) {
            
            $('input[name=deposit]').val(-due);
            $('input[name=due]').val(0);
            
        }
        else {
            $('input[name=due]').val(due);
            $('input[name=deposit]').val(0);
        }
    });
</script>
@endpush


@php
    $agents = \App\Models\Agent::pluck('agent_name', 'id');
    $ledgers = \App\Models\Ledger::get();
@endphp




@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active">{{__('Agent Ledger')}}</li>

@endsection

@section('action-btn')
    <div class="float-end">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createAgent">
        <i class="ti ti-plus"></i>
        </button>
    </div>
@endsection

@section('modal')

@endsection

@section('content')

<div class="modal fade" id="createAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form method="post" action="{{ route('ledger.store') }}">
  @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Ledger Information</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
                <div class="form-group">
                    <label for="agent_id" class="form-label">Agent</label>
                    <select name="agent_id" class="form-control" required>
                        <option value="" disabled selected>Select Agent</option>
                        @foreach($agents as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" required>


                    
                </div>
                <div class="form-group">
                    <label for="paid_for" class="form-label">Paid For</label>
                    <input type="text" name="paid_for" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="unit_price" class="form-label">Unit Price</label>
                    <input type="number" name="unit_pirce" class="form-control" required>
</div>
                <div class="form-group
                ">
                    <label for="number_of_unit" class="form-label
                    ">Number of Unit</label>
                    <input type="number" name="number_of_unit" class="form-control" required>
</div>

                
                <div class="form-group">
                    <label for="amount" class="form-label
                    ">Amount</label>
                    <input type="number" name="amount" class="form-control" required>
</div>
<div class="form-group">
                    <label for="payment_mode" class="form-label
                    ">Payment Mode</label>
                    <input type="text" name="payment_mode" class="form-control" required>
</div>


                <div class="form-group">
                    <label for="advance" class="form-label
                    ">Advance</label>
                    <input type="number" name="advance" class="form-control" required>
</div>
                <div class="form-group">
                    <label for="deposit" class="form-label
                    ">Deposit</label>
                    <input type="number" name="deposit" class="form-control" required>
</div>
                    
                    <div class="form-group
                    ">
                        <label for="due" class="form-label
                        ">Due</label>
                        <input type="number" name="due" class="form-control" required>
</div>
                    <div class="form-group
                    ">
                        <label for="refund" class="form-label
                        ">Refund</label>
                        <input type="number" name="refund" class="form-control" value="0" required>
</div>

                
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Add Ledger"></input>
      </div>
    </div>
    </form>
  </div>
</div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                
                                <th>{{__('Agent')}}</th>
                                <th>{{__('Action')}}</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ledgers->unique('agent_id') as $ledger)
                                <tr>
                                    <!-- If agent name show name, else show N/A -->
                                    <td>{{ $ledger->agent ? $ledger->agent->agent_name : 'N/A' }}</td>
                                    
                                    <td>
                                  
    <a class="btn btn-sm align-items-center  btn-success" href="/ledger/agent?agent_id={{ $ledger->agent ? $ledger->agent->id : '0' }}" data-bs-toggle="tooltip" title="{{__('View')}}">
        <i class="ti ti-eye text-white"></i>
    </a>
    


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

