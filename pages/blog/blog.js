// blog.js

// Newsletter form handler
document.addEventListener("DOMContentLoaded", () => {
  const newsletterForm = document.querySelector(".newsletter-form");
  const emailInput = newsletterForm.querySelector("input[type='email']");

  newsletterForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const email = emailInput.value.trim();

    if (validateEmail(email)) {
      alert(`Thank you for subscribing: ${email}`);
      emailInput.value = "";
    } else {
      alert("Please enter a valid email address.");
    }
  });
});

function validateEmail(email) {
  const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return pattern.test(email);
}
