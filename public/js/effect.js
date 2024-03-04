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

//gestion nbr de mots dans trix
document.addEventListener("trix-initialize", function(event) {
  var editor = event.target.editor;
  editor.setMaxLength(1000); 
});

