<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        #slideshow {
      opacity: 1;
      transition: opacity 0.5s ease-in-out;
    }
    </style>

</head>
<body>
     <section class="popup" id="confirmation">
            <img src="./assets/images/icon-order-confirmed.svg" alt="">

            <h2>Order Confirmed</h2>
            <h6>We hope you enjoy your food !</h6>
            <div class="itemsBought">

            </div>
            <p class="popup-total">Order Total: <span class="total-price">0</span></p>
            <button id="confirme-popup">Start New Order</button>
     </section>
</body>