@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">All Loan</li>
                <li class="list-group-item">Paid Load</li>
            </ul>
        </div>
        <div class="col-md-10">
            <div style="margin-bottom: 10px; margin-top:5px; text-align:right">
                <button type="button" class="btn btn-primary" data-backdrop="static" data-toggle="modal" data-target="#exampleModalScrollable">
                    Add Loan
                  </button>
            </div>
           
              
            <table class="table table-light table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Action</th>
                        <th>Loan Amount</th>
                        <th>Interest Payable</th>
                        <th>Total Refundable</th>
                        <th>Effective Date</th>
                        <th>Liquidation Date</th>
                        <th>Active</th>
                        <th>Paid</th>
                        
                    </tr>
                </thead>
                <tbody>
                  @if (isset($loans) && $loans->count() > 0)
                      @foreach ($loans as $loan)
                          <tr>
                            <td>
                              @if ($loan->isactive)
                              <button id="activateLoan{{ $loan->id }}" disabled  class="btn btn-primary btn-sm activateLoan">Activated</button>    
                              @else
                              <button id="activateLoan{{ $loan->id }}" onclick="activateLoan({{ $loan->id }})"  class="btn btn-primary btn-sm activateLoan">Activate</button>
                              @endif
                              
                              <button class="btn btn-success btn-sm">Pay</button>
                            </td>
                            <td>{{ number_format((int)$loan->loanamount, 2, '.', ',') }}</td>
                            <td>{{ number_format((int)$loan->interestpayable, 2, '.', ',') }}</td>
                            <td>{{ number_format((int)$loan->totalrefundable, 2, '.', ',') }}</td>
                            <td>{{ $loan->startdate->format('M d, Y') }}</td>
                            <td>{{ $loan->enddate->format('M d, Y') }}</td>
                            <td>{{ $loan->isactive == 0 ? "False" : "True" }}</td>
                            <td>{{ $loan->paid == 0 ? "False" : "True" }}</td>
                           
                          </tr>
                      @endforeach
                  @else
                      
                  @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
   

   
  <!-- Modal -->
  <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-bold" id="exampleModalScrollableTitle">Loan Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('loan') }}">
        <div class="modal-body">
          {{-- <div class="row"> --}}
            
                {{ csrf_field() }}
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div> 
                @endif
           
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                    <label for="my-input" class="">Principal Amount</label>
                    <input type="text" class="form-control" id="loanamount" name="loanamount" aria-describedby="emailHelp" placeholder="Enter Amount">
                </div>
              </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="my-input" class="">Interest Amount (%)</label>
                        <input type="text" class="form-control" id="interest" readonly name="interest" value="5.0">
                    </div>
                </div>
            </div>

            {{--  --}}

            <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="my-input" class="">Loan Period (Month)</label>
                      <input type="number" value="1" class="form-control" id="loanperiod" name="loanperiod">
                  </div>
                </div>
                  <div class="col-md-6">
                      <div class="form-group">
                      <label for="my-input" class="">Interest Payable</label>
                      <input type="text" readonly class="form-control" id="interestpayable" name="interestpayable">
                      </div>
                  </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="my-input" class="">Effective Date</label>
                    <input type="date" class="form-control" id="startdate" name="startdate">
                    
                  </div>
                </div>
                  {{-- <div class="col-md-6">
                      <div class="form-group">
                        <label for="my-input" class="">Liquidation Date</label>
                        <input type="date" class="form-control" id="enddate" name="enddate">
                      </div>
                  </div> --}}
                  <div class="col-md-6">
                    <div class="form-group">
                    <label for="my-input" class="">Total Refundable</label>
                    <input type="text" readonly class="form-control" id="totalrefundable" name="totalrefundable">
                    </div>
                </div>
              </div>
        </div>
           
        <div class="modal-footer text-center">
            <button class="btn btn-primary" type="submit">Add Loan</button>
            <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </form>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('script')
  <script>  
    $(document).ready(function () {
        $('#loanamount').change(function () { 
          let principalAmount =  $('#loanamount').val()
          const interestAmount =  $('#interest').val() == 5 ? 0.05 : 0.05;
          if(principalAmount !=='' || principalAmount !==null && !isNaN(principalAmount) ){
            $('#interestpayable').val(parseFloat(principalAmount) * parseFloat(interestAmount));
            $('#totalrefundable').val(parseFloat($('#interestpayable').val()) + parseFloat(principalAmount));
            
          }
        });
        /*
        $('#startdate').change(function () { 
          
          let loanPeriod =  $('#loanperiod').val()
          let effectiveDate =  $('#startdate').val();
          let newDate = new Date(effectiveDate);
          // newDate.setDate(newDate.getMonth()+parseInt(loanPeriod));

          let day = ("0" + newDate.getDate()).slice(-2);
          let month = ("0" + (newDate.getMonth() + 1+ parseInt(loanPeriod))).slice(-2);

          let liquidationDate = month +"/"+ (day) +"/"+newDate.getFullYear();


          $('#enddate').val(month +"/"+ (day) +"/"+newDate.getFullYear());
          $('#enddate').val(newDate.getFullYear()+"/"+(month) +"/"+ (day));

          // alert(newDate);
          console.log(Date.parse(liquidationDate));
        });
        */

      // console.log($('#activateLoan'+id).val());
      // $("#activateLoan").click(function () { 
      //   activateLoan($('#activateLoan').val());  
                
      // });

    
    });

    function activateLoan(id){
        $.ajax({
                url: "/approve/"+id,
                //{{ route('loan.approve', ['id'=>"loanID"])}}",
                type: 'GET',
                contentType: 'application/json',
                data: id,
                 success:function(data){
                   console.log(id);
                     console.log(data)
                     $('#activateLoan'+id).attr('disabled', true)
                     $('#activateLoan'+id).html('processing...');
                     if(data.status){                         
                        setTimeout(function()
                        {
                        alert(data.message);
                        location.reload();
                        },2000);
                        
                     }
                     else{
                        // one loan is active already
                         setTimeout(function()
                         {
                          alert(data.message);
                          location.reload();
                         },2000);
                         
                     }
                    
                 }, 
                 error: function(xhr, status,error){
                     console.log(error)
                 }
             })
      }
  </script>
    
@endsection