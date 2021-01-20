<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'loanamount', 'interest', 'loanperiod', 'interestpayable', 'totalrefundable', 'startdate', 'enddate', 'userid'
    ];

    protected $dates = [
        'startdate', 'enddate'
    ];
}
