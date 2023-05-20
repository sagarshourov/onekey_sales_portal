<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaConversion;

class WaController extends BaseController
{
  //

  private function get_all()
  {

    return WaConversion::get()->groupBy('name');
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //

    return $this->sendResponse($this->get_all(), 'Chat Retrieve successfully.');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }


  private function send_messasge($msg, $to)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://graph.facebook.com/v15.0/116203211365460/messages',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{ "messaging_product": "whatsapp", "to": "' . $to . '", "type": "text", "text": { "body": "' . $msg . '" } }',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer EAAX23vGL2ugBAEEneE2Il5NxZCn4cuZB2nliDXadt9RbMjBeGkc8VeDZCl8Y1y9iHNBl6M9YQKoedL1mtZB2eaLoR5HQdtjerIx5p3zAC7NNPhvMCSMjSTpbZCbIk9w9XlPzlreqkRJQ3ICyIVYq1rCvNZC722MZAdIePu3CN9XxBAuAfi63jG3fnc9IzjZALnneJc2ng9xP9AZDZD',
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
  }





  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    WaConversion::create($request->all());

    $this->send_messasge($request->text, $request->to);

    return $this->sendResponse($this->get_all(), 'Chat created successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($table, $id)
  {
  }
}
