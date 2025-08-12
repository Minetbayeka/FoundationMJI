const buttonPayment = document.querySelector('.btn');

const buttonContent = `
<div class="payment-options">
        <button class="option active" onclick="window.location.href='../payments/payment.html'">Credit Card</button>
        <button class="option" onclick="window.location.href='../payments/sapa.html'">SEPA</button>
        <button class="option" onclick="window.location.href='../payments/payPal.html'">PayPal</button>
        <button class="option" onclick="window.location.href='../payments/mobileMoney.html'">Mobile Money</button>
      </div>`


buttonPayment.innerHTML = buttonContent;