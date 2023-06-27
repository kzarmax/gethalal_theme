<?php

?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>GetHalal Paypal Pay</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AWePcvOCLDJYGCtHglfn9FVdjREvFT2kSFNrYCRdN2VdKhTOyqIKO1H502P9VsvaBYfq2Xn6SBo6UEWD&currency=EUR"></script>
    <style>
        body,
        html{
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #3d40ad 0, #6b6ed7 100%)
        }
        .container {
            height: 100%;
            display: flex;
            margin-left: 20px;
            margin-right: 20px;
            overflow-y: scroll;
            justify-content: center;
            align-items: center
        }
        p{
            color: #fff;
            font-size: 16px;
            text-align: justify;
            margin-bottom: 50px
        }
        #preloaderSpinner {
            display: none
        }
    </style>
</head><!--  -->
<body>
<div class="container">
    <div style="justify-content: center; text-align: center">
        <p>Loading...</p>
        <div id="paypal-button-container"></div>
    </div>
</div>
<script>
    var Errors = ()=> console.error('Please Supply valid Props');
    function payWithPayPal(e) {
        paypal.Buttons({
            createOrder: function(n, t) {
                return t.order.create({
                    purchase_units: [{
                        amount: {
                            value: e
                        }
                    }]
                })
            },
            onAuthorize: function(e, n) {
                return n.payment.execute().then(function() {
                    window.alert("Payment Complete!")
                })
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    window.ReactNativeWebView.postMessage(JSON.stringify({
                        transaction: details,
                        message: "Transaction Successful",
                        status: "Success"
                    }))
                });
            },
            onCancel: function(e) {
                window.ReactNativeWebView.postMessage(JSON.stringify({
                    transaction: {},
                    message: "Transaction failed",
                    status: "Failed"
                }))
            }
        }).render("#paypal-button-container")
    }

    var userAgent = window.navigator.userAgent.toLowerCase(),
        safari = /safari/.test( userAgent ),
        ios = /iphone|ipod|ipad/.test( userAgent );

    if(ios && !safari){
        window.addEventListener("message", function(e) {
            var n = JSON.parse(e.data);
            if(null == n.ProductionClientID || null == n.orderID || null == n.amount){
                Errors();
                return;
            }
            document.querySelector("p").innerText = "You are about to fund your wallet with " + n.amount + ". Click on any of the payment options to proceed.", payWithPayPal(n.amount)
        });
    } else {
        document.addEventListener("message", function(e) {
            var n = JSON.parse(e.data);
            if(null == n.ProductionClientID || null == n.orderID || null == n.amount){
                Errors();
                return;
            }
            document.querySelector("p").innerText = "You are about to fund your wallet with " + n.amount + ". Click on any of the payment options to proceed.", payWithPayPal(n.amount)
        });
    }
</script>
</body>

</html>
