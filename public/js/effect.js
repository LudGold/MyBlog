document.addEventListener("DOMContentLoaded", function () {
  const checkbox = document.querySelector('input[name="consent"]');
  const submitBtn = document.querySelector('input[type="submit"]');

  checkbox.addEventListener("change", function () {
    if (this.checked) {
      submitBtn.classList.add("active");
    } else {
      submitBtn.classList.remove("active");
    }
  });
});
