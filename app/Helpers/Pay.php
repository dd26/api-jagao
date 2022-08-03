<?php
namespace App\Pays;

use App\{ Payment, Card };



class Pay {

    public static function createCharge($card_id, $amount) {
        // crear el new stripe con la key de stripe en el .env del proyecto
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        $card = Card::find($card_id);

        // sacar el mes exp de la tarjeta ya que esta en formato MM/YY
        $exp_month = substr($card->expiration_date, 0, 2);

        // sacar el año exp de la tarjeta ya que esta en formato MM/YY y agregarle 20 al año
        $exp_year = substr($card->expiration_date, -2);
        return $exp_month;
        // crear el token de pago
        $token = $stripe->tokens->create([
            'card' => [
                'number' => $card->number,
                'exp_month' => $card->exp_month,
                'exp_year' => $card->exp_year,
                'cvc' => $card->cvv,
            ],
        ]);

        // crear el pago
        $charge = $stripe->charges->create([
            'amount' => $amount,
            'currency' => 'usd',
            'description' => 'Pago de Servicios JAGAO APP',
            'source' => $token['id'],
        ]);
    }
}
