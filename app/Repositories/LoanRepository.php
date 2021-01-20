<?php 


namespace App\Repositories;


use App\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RepositoryInterface;

class LoanRepository implements RepositoryInterface
{

    public function find($id)
    {
        return Loan::where('id','=', $id)->get();
    }

    public function save($attributes)
    {
        return Loan::create($attributes);
    }


    public function all()
    {
        return Loan::where('userid', '=', Auth::User()->id)->orderBy('startdate', 'asc')->get();
    }

    public function update($id, $column, $val)
    {
        return DB::table('loans')
        ->where([
            'id' => $id,
            // 'userid' => Auth::User()->id
        ])
        ->update([
            $column => $val
        ]);
       
    }

    public function isPaymentDue()
    {
        $checkLoan = Loan::where('userid', '=', Auth::User()->id)->where('isactive', '=', 1)->where('paid', '=', 0)->get();
        $currentDate = date("Y-m-d");

        if($checkLoan){
            if($currentDate > $checkLoan[0]->enddate && $checkLoan[0]->paid == 0){
                $totalRefund = $this->latePaymentPenalty($checkLoan[0]->totalrefundable);
                return $this->update($checkLoan[0]->id, 'totalrefundable', $totalRefund);
            }
        }
    }

    public function latePaymentPenalty($amount)
    {
        $penaltyFee = (int)$amount * 0.005;
        return $penaltyFee + $amount;
    }

  

}




?>