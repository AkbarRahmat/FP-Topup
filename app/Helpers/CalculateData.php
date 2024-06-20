<?php

if(!function_exists('calculateTransactionTotalCost')){

    function calculateTransactionTotalCost(&$data, $copyToPaid = false){
        $data['total_cost'] = $data['product_price'] + $data['seller_cost'] + $data['service_cost'];
        if ($copyToPaid) {
            $data['paid_price'] = $data['total_cost'];
        }
    }
}

if(!function_exists('calculateTransactionDebtAndRefund')){

    function calculateTransactionDebtAndRefund(&$data){
        $data['refund_cost'] = 0;
        $data['debt_cost'] = 0;

        $remaining_cost = $data['paid_price'] - $data['total_cost'];

        if ($remaining_cost > 0) {
            $data['refund_cost'] = $remaining_cost;
        } elseif ($remaining_cost < 0) {
            $data['debt_cost'] = $remaining_cost * -1;
        }
    }
}


