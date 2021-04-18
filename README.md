# SUPEXO-Generic-API
steps to use this API
For testing enviroment you need to replace supexo.io with staging.supexo.io
1.you need to communicate with us "https://supexo.io/" to get a BTB user name and password.
3.you need to provide us with the following three APIs from your system:
  a.Get Address API:
        POST
        Parameters:
            currency
            username
            password
        Success Response:
            {
                'status' => 1,
                'address' => '<the currency address>'
            }
        Fail Response:
            {
                'status' => 0,
                'message' => 'error message'
            }
  b.Get Balance
        POST
        Parameters:
            source_address
            username
            password 
        Success Response
            {
                'status' => 1,
                'address' => '<address>',
                'currency' => 'LTC',
                'balance' => 0.02
            }
        Fail Response
            {
                'status' => 0,
                'message' => "<error message>"
            }
  c.Send Amount:
        POST
        Parameters
        username
        password
        source_address
        destination_address
        amount

        Success Response
        {
        'status' => 1,
        'destination_address' => '<address>',
        'currency' => 'LTC',
        'sent_amount' => 0.02
        }
        Fail Response:
        {
            'status' => 0,
             'message' => 'error message'
        }



4.to create a supexo transaction from your wallet you need to do the following steps 
  a. you need to call the auth API:
      Auth API:
      URL: supexo.io/?rest_route=/simple-jwt-login/v1/auth
      Method:
      POST
      Parameters:
      email: <email>
      password: <password>
      Response:

      {
      data: {
          jwt:    "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTg3ODEwMTAsImV4cCI6NzYxODc4MTAxMCwiZW1haWwiOiJuYWxhd25laEBpdGdzb2Z0d2FyZS5jb20ifQ.rHkrV6eBFQ1VrXEQ_VVDBhBkXdajQfdXRL1MB0roMIo"
      },
      success: true
      }
  b. You need to call our general API by passing to it the jwt token  
			Create Transaction API:
			URL: supexo.io/wp-json/supexo/create_transaction
			Method:
			POST
			Parameters:
			sending_address: <source address>,
            currency: 'BTC',
			receiving_address: <destination address>,
			amount: <sending amount>
			Headers:
			Authorization: "Bearer <jwt code>"
			Resposne:
			{
					status: true
			}
