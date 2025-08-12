const paymentOptions = document.querySelectorAll(".payment-options .option");

  paymentOptions.forEach(option => {
    option.addEventListener("click", () => {
      // Remove active from all
      paymentOptions.forEach(opt => opt.classList.remove("active"));
      // Add active to clicked one
      option.classList.add("active");
    });
  });

  // Form validation
  const form = document.querySelector("form");

  form.addEventListener("submit", function(e) {
    e.preventDefault(); // Prevent actual submission for now

    const name = document.getElementById("name").value.trim();
    const cardNumber = document.getElementById("card-number").value.trim();
    const expiry = document.getElementById("expiry").value.trim();
    const cvc = document.getElementById("cvc").value.trim();

    if (!name || !cardNumber || !expiry || !cvc) {
      alert("Please fill in all required fields.");
      return;
    }

    alert("Payment submitted successfully!");
    form.reset();
    document.querySelector(".option").classList.add("active"); // Reset default payment option
  });