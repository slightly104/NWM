document.addEventListener('DOMContentLoaded', function() {
  const menuCheckbox = document.getElementById('side-menu');
  const menuLinks = document.querySelectorAll('.menu a');

  menuLinks.forEach(link => {
    link.addEventListener('click', function() {
      menuCheckbox.checked = false;
    });
  });
});