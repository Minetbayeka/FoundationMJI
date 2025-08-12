// Utility function to handle active button states
function setupButtonGroup(buttonSelector) {
  const buttons = document.querySelectorAll(buttonSelector);

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      // Remove 'active' from all buttons in group
      buttons.forEach(btn => btn.classList.remove('active'));
      // Add 'active' to clicked button
      button.classList.add('active');
    });
  });
}

// Set up all button groups
document.addEventListener("DOMContentLoaded", () => {
  setupButtonGroup('.amount-buttons button');
  setupButtonGroup('.frequency-buttons button');
  setupButtonGroup('.identity-buttons button');
});
