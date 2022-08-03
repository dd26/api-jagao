<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// helpers
use App\Helpers\Helper;

class PaymentController extends Controller
{
    public function pruebasPagosStripe ()
    {
        try {
            $test = Helper::createCharge(2, 100, 26);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json($test);


        /* // crear payment method obj
        $paymentMethodObj = [
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 7,
                'exp_year' => 2023,
                'cvc' => '314'
            ]
        ];
        // crear payment method
        $paymentMethod = $stripe->paymentMethods->create($paymentMethodObj);

        // crear customer obj
        $customerObj = [
            'description' => 'My First Test Customer',
            'payment_method' => $paymentMethod,
        ];

        // crear customer
        $customer = $stripe->customers->create($customerObj);

        $objectSessionCreate = [
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'T-shirt'
                        ],
                        'unit_amount' => 2000
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
        ];
        $session = $stripe->checkout->sessions->create($objectSessionCreate);
        return response()->json($session); */
    }
}
