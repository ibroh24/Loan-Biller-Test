<?php

namespace App\Http\Controllers;

use App\Loan;
use Paystack;
use App\Jobs\LoanBillJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\LoanRepository;



class LoanController extends Controller
{
    private $loanRepository;
    
    public function __construct(LoanRepository $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }

    public function index()
    {
        return view('home', [
            'loans' => $this->loanRepository->all()
        ]);
    }

    public function newLoan(Request $request)
    {
        $request->validate=([
            'loanamount'=> 'required',
            'interest'=> 'required',
            'interestpayable'=> 'required',
            'totalrefundable'=> 'required',
            'startdate'=> 'required',
            'loanperiod'=> 'required',
            // 'userid'=> 'required',
        ]);

        $saveLoan = [];
        foreach ($request->all() as $key => $value) {
            $saveLoan[$key] = $value;
        }

        $startDate = strtotime($request->startdate);
        $endDate = date("Y-m-d", strtotime("+".(int)$request->loanperiod. " month", $startDate));

        unset($saveLoan['_token']);
       
        $saveLoan['enddate'] = $endDate;
        $saveLoan['userid'] = Auth::User()->id;

        
        // dd($saveLoan);
                
        $this->loanRepository->save($saveLoan);
        
        return redirect()->back();
    }

    function approveLoan($id)
    {
        $loanData = $this->loanRepository->find($id);
        
        if($loanData){
            // get and checks all user loan
            $allUserLoan = $this->loanRepository->all();
            // $check = [];
            for ($i=0; $i < count($allUserLoan); $i++) { 
                // $check = $allUserLoan[$i];
                if($allUserLoan[$i]->isactive == 1 && $allUserLoan[$i]->paid != 1 ){
                    return response()->json([
                        'status' => false, 'message' => ucwords('an active loan is still available')
                    ]);
                }
            }
            // return json_encode($check);

            // then update/activate current find
            $this->loanRepository->update($id, 'isactive', 1);
            return response()->json([
                'status' => true, 'message' => ucwords('Loan Approved Successfully')
            ]);
        }
       
        
    }

    public function runPaymentCheck()
    {
        LoanBillJob::dispatch();
    }

    // payment
    public function redirectToGateway()
    {

        
        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            return Redirect::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }        
    }

    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        // dd($paymentDetails);
        if($paymentDetails['status']){
            // then update the loan table
            $id = $paymentDetails['data']['metadata']['key_name'];
            $this->loanRepository->update($paymentDetails['data']['metadata']['loan_id'], 'paid', 1);
            return redirect()->route('home', ['userid' => $id]);
        }
       
    }
}
