<?php
//  require __DIR__  . '/vendor/autoload.php';

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MercadoPago;
use MercadoPago\Payment;
use MercadoPago\Client;
use MercadoPago\Card;
// use Illuminate\Support\Facades\Input;
MercadoPago\SDK::setAccessToken("TEST-2328908812279734-070509-4e87e2b552a20611336e91b2ae03743e-530429288"); // Either Production or SandBox AccessToken


class MpController extends Controller
{

    public function mercadoPago(Request $request){

     $form = $_REQUEST['json'];
     $formDecoded = json_decode($form);
 
      $float = floatval($formDecoded->transaction_amount);
      $integer = intval($formDecoded->installments);
      // var_dump($formDecoded);
     $payment = new MercadoPago\Payment();
  
      $payment->transaction_amount = $float;
      $payment->token = $formDecoded->token;
      $payment->description = $formDecoded->description;
      $payment->installments = $integer;
      $payment->payment_method_id = $formDecoded->payment_method_id;
      $payment->issuer_id = $formDecoded->issuer;
      // $payment->additional_info = array(
      //   "items" =>$formDecoded->additional_info->items,
      // );
      $payment->payer = array(
        "email" => $formDecoded->email,
        "identification" => array(
          "type" => $formDecoded->identification->type,
          "number" =>$formDecoded->identification->number
       )
     );
    //$payment->additional_info = $formDecoded->additional_info;    
    // $payment->external_reference = $formDecoded->order_id;
     //$payment->notification_url = "http://localhost:8100/";
      
      $payment->save();

      $paymentResponse = [
        "status"=>$payment->status,
        "status_detail"=>$payment->status_detail,
        "id"=>$payment->id,
        "payment_method_id"=>$payment->payment_method_id,
        "order"=>$payment->external_reference,
        "issuer_id"=>$payment->issuer_id 
        // "additional_info"=>$payment->additional_info
        // "payer"=>array(
        //   "email"=>$payment->payer->email,
        //   "identification"=>$payment->payer->identification->number)
          ];
      $paymentEncoded = json_encode($paymentResponse);
      echo $paymentEncoded;
    }

    public function payment(Request $request){

      $form = $_REQUEST['json'];
      $formDecoded = json_decode($form);
  
      $float = floatval($formDecoded->transaction_amount);
      //  $integer = intval($formDecoded->installments);
       // var_dump($formDecoded);
      $payment = new MercadoPago\Payment();
   
       $payment->transaction_amount = $float;
       $payment->token = $formDecoded->token;
      //  $payment->description = $formDecoded->description;
       $payment->installments = 1;
      //  $payment->payment_method_id = $formDecoded->payment_method_id;
      //  $payment->issuer_id = $formDecoded->issuer;
       // $payment->additional_info = array(
       //   "items" =>$formDecoded->additional_info->items,
       // );
       $payment->payer = array(
         "type"=>"customer",
         "id"=>$formDecoded->id
        //  "email" => $formDecoded->email,
        //  "identification" => array(
        //    "type" => $formDecoded->identification->type,
        //    "number" =>$formDecoded->identification->number
        // )
      );
     //$payment->additional_info = $formDecoded->additional_info;    
     // $payment->external_reference = $formDecoded->order_id;
      //$payment->notification_url = "http://localhost:8100/";
       
       $payment->save();
 
       $paymentResponse = [
         "status"=>$payment->status,
         "status_detail"=>$payment->status_detail,
         "id"=>$payment->id,
         "payment_method_id"=>$payment->payment_method_id,
         "order"=>$payment->external_reference,
         "issuer_id"=>$payment->issuer_id 
         // "additional_info"=>$payment->additional_info
         // "payer"=>array(
         //   "email"=>$payment->payer->email,
         //   "identification"=>$payment->payer->identification->number)
           ];
       $paymentEncoded = json_encode($paymentResponse);
       echo $paymentEncoded;
     }

    public function mercadoPagoClient(Request $request){
      // $form = $_REQUEST['json'];
      //  $formDecoded = json_decode($request);
      // var_dump($formDecoded);
      
      $customer = new MercadoPago\Customer();
      $customer->email =$request->customer;
      $customer->save();
      // $id= $customer;       
      $card = new MercadoPago\Card();
      $card->token = $request->card;
      $card->customer_id =$customer->id;
      $card->save();

      $customerResponse =[
        "id"=>$customer->id,
        "default_card"=>$customer->default_card,
        "cards"=>$customer->cards
      ];
      // var_dump($card);
      // die;
      $customerEncoded = json_encode($customerResponse);
      echo $customerEncoded;
      // saveCard()
    }

    public function saveCard(Request $request){

      $card = new MercadoPago\Card();
      $card->token = $request->token;
      $card->customer_id = $request->id;
      $card->save();


      $customerResponse =[
        "id"=>$customer->id,
         "default_card"=>$customer->default_card,
         "cards"=>$customer->$cards
      ];
      $customerEncoded = json_encode($customerResponse);
      return $customerEncoded;
    }

    public function addCards(Request $request){

      $email=$request->id;
      $filters=array("email"=>$email);
      // $customer = MercadoPago\Customer::find_by_id($id);
      // var_dump($id );
      // die;
      $customer = MercadoPago\Customer::search($filters);

      $card = new MercadoPago\Card();
      $card->token = $request->token;
      $card->customer_id = $customer->id;
      $card->save();

      $cardEncoded = json_encode($card);
      // var_dump($cards );
      // die;
      return  $cardEncoded;
    }


    public function getClientCards(Request $request){

      $email=$request->id;
      $filters=array("email"=>$email);
      // $customer = MercadoPago\Customer::find_by_id($id);
      // var_dump($id );
      // die;
      $customer = MercadoPago\Customer::search($filters);
      // var_dump( $customer);
      // die;
      // if(sizeof($customer) !=0){
        $cards = $customer[0]->cards;
        $cardsEncoded = json_encode($cards);
        return $cardsEncoded;
      // }else{
      //   return false;
      // }
    }

    public function getClient(Request $request){

      $email=$request->id;
      $filters=array("email"=>$email);
      $customer = MercadoPago\Customer::search($filters);
      var_dump($customer[0]);
       die;
     
      $customerEncoded = json_encode($customer['storage'][0]);

      return  $customerEncoded;
    }
}
