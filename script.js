document.addEventListener('DOMContentLoaded', function() {
  const menuCheckbox = document.getElementById('side-menu');
  const menuLinks = document.querySelectorAll('.menu a');

  menuLinks.forEach(link => {
    link.addEventListener('click', function() {
      menuCheckbox.checked = false;
    });
  });
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('send-form-smtp.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                form.reset();
            } else {
                alert('Ошибка: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка. Попробуйте ещё раз.');
        });
    });
});
