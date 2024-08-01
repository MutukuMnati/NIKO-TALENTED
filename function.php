<?php
function getAccessToken() {
    $consumerKey = 'RHklVemhWKd7EjjT6PssgFUaHFnBd7yvALQbaRpCX9dA8h85';
    $consumerSecret = 'W9vcy0zdEpRz3izClOpbYVcEfTCtahCnPdoCA0UnCezuH7ibZf2HnxQDT9IVrnKs';
    $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
    $url = 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($curl);
    $result = json_decode($response);

    curl_close($curl);

    return $result->access_token;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $amount = $data['amount'];
    $phone = $data['phone'];
    $billRefNumber = $data['billRefNumber'];
    $shortCode = 'your_shortcode';

    $accessToken = getAccessToken();
    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

    $paymentData = array(
        'ShortCode' => $shortCode,
        'CommandID' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'Msisdn' => $phone,
        'BillRefNumber' => $billRefNumber
    );

    $dataString = json_encode($paymentData);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $accessToken));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($curl);
    curl_close($curl);

    echo json_encode(['message' => 'Donation request sent. Please check your phone to complete the transaction.']);
}
?>
