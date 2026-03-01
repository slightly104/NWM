document.addEventListener('DOMContentLoaded', function() {
  const menuCheckbox = document.getElementById('side-menu');
  const menuLinks = document.querySelectorAll('.menu a');

  menuLinks.forEach(link => {
    link.addEventListener('click', function() {
      menuCheckbox.checked = false;
    });
  });
});

function validatePhoneInput(phone) {
    const phoneRegex = /^(\+7|8)[\d\s\-\(\)]{10,15}$/;
    return phoneRegex.test(phone.trim());
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const phoneInput = document.getElementById('phone');
        const phoneValue = phoneInput.value.trim();
        
        if (!validatePhoneInput(phoneValue)) {
            alert('Введите корректный номер телефона (пример: +7 999 123-45-67)');
            phoneInput.focus();
            return;
        }
        
        const formData = new FormData(form);
        
        fetch('send-form.php', {
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
