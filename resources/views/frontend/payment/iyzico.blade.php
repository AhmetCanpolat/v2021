<html>
  <head>
    <title>İyzico ödeme</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    .loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid #3498db;
      width: 120px;
      height: 120px;
      -webkit-animation: spin 2s linear infinite; /* Safari */
      animation: spin 2s linear infinite;
      margin: auto;
    }

    /* Safari */
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    </style>
  </head>
  <body>
    <button id="checkout-button" style="display: none;"></button>
    <div class="loader"></div>
    <div>
        @foreach($)
        {!! $paymentinput !!}
    </div>
    <br>
    <br>
    <p style="width: 250px; margin: auto;">İyzico</p>

  </body>
</html>
