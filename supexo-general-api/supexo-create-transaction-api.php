<?php

require_once ABSPATH . '/wp-content/plugins/simple-jwt-login/src/modules/SimpleJWTLoginService.php';
require_once ABSPATH . '/wp-content/plugins/simple-jwt-login/routes.php';

const SUPEXO_BASE_URL = "http://supexo.wp.io/";



add_action('rest_api_init', function ($data) {
	
	register_rest_route('supexo', '/create_transaction', 
		array(
			'methods' => 'POST',
			'callback' => 'create_transaction_handler',
		 )
	);
});


/**
 * POST API to start transation coins.
 * 
 * @param sending_address: The wallet sender address.
 * @param pairs: The transation pairs.
 * @param receiving_address: The final destination.
 * @param amount: The transaction amount.
 */
function create_transaction_handler() {
    
    $sending_address = filter_input(INPUT_POST, 'sending_address', FILTER_SANITIZE_STRING); // The wallet source address.
    $pairs = filter_input(INPUT_POST, 'pairs', FILTER_SANITIZE_STRING); // The transation pairs.
    $receiving_address = filter_input(INPUT_POST, 'receiving_address', FILTER_SANITIZE_STRING); // The final destination.
    $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING); // The transaction amount.

    $header = apache_request_headers();

    if (empty($header['Authorization'])) {
        return new WP_REST_Response([
            'status' => false,
            'message' => 'UnAuthorization access'
        ], 401);
    }

    $data=[
        'sslverify' => false,
        'headers' => [
            'Authorization' => $header['Authorization']
        ],
        'body' => [
            'sending_address' => $sending_address,
            'pairs' => $pairs,
            'receiving_address' => $receiving_address,
            'amount' => $amount
        ]
    ];

    

    $response = wp_remote_post( SUPEXO_BASE_URL . "wp-json/supexo/v1/post/create_supexo_transaction", $data);
    
    $responseBody = json_decode($response['body'], true);
    
    if (isset($responseBody['success'])) {
       if ($responseBody['success']) {
            return  new WP_REST_Response([
                'status' => true,
                'order_id' => $responseBody['data']['orderId']
            ], 200);
       }
       return  new WP_REST_Response([
            'status' => false,
            'error_details' => $responseBody['data']
       ], 200);
    }
    return new WP_REST_Response([
        'status' => false,
        'message' => 'There are an unknown error, please try again later'
    ], 400);
}