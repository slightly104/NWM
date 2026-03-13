document.addEventListener('DOMContentLoaded', function() {
    const menuCheckbox = document.getElementById('side-menu');
    const menuLinks = document.querySelectorAll('.menu a');

    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            menuCheckbox.checked = false;
        });
    });

    // Функция форматирования номера телефона
    function formatPhoneNumber(value) {
        let numbers = value.replace(/\D/g, '');

        if (numbers.startsWith('8')) {
            numbers = '7' + numbers.slice(1);
        }

        if (!numbers.startsWith('7')) {
            numbers = '7' + numbers;
        }

        const match = numbers.match(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/);

        if (match) {
            return `+${match[1]} (${match[2]}) ${match[3]}-${match[4]}-${match[5]}`;
        }

        return '+7 (' + numbers.substring(1, 4) + (numbers.length > 4 ? ') ' + numbers.substring(4, 7) : '') +
               (numbers.length > 7 ? '-' + numbers.substring(7, 9) : '') +
               (numbers.length > 9 ? '-' + numbers.substring(9, 11) : '');
    }

    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value;

        if (!value.startsWith('+7') && !value.startsWith('8') && value.length > 0) {
            value = '+7';
            e.target.value = value;
        }

        e.target.value = formatPhoneNumber(value);
    });

    function validatePhoneInput(phone) {
        const phoneRegex = /^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/;
        return phoneRegex.test(phone.trim());
    }

    // Валидация имени
    function validateNameInput(name) {
        const nameValue = name.trim();
        if (nameValue.length === 0) {
            return 'Поле не может быть пустым';
        }
        if (nameValue.length < 2) {
            return 'Имя должно содержать минимум 2 символа';
        }
        return true;
    }

    const form = document.getElementById('contactForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const phoneInput = document.getElementById('phone');
        const phoneValue = phoneInput.value.trim();
        const errorElement = document.getElementById('phoneError');

        const nameInput = document.getElementById('name');
        const nameValue = nameInput.value.trim();
        const nameErrorElement = document.getElementById('nameError');

        errorElement.style.display = 'none';
        errorElement.textContent = '';
        nameErrorElement.style.display = 'none';
        nameErrorElement.textContent = '';

        // Валидация имени
        const nameValidationResult = validateNameInput(nameValue);
        if (nameValidationResult !== true) {
            nameErrorElement.textContent = nameValidationResult;
            nameErrorElement.style.display = 'block';
            nameInput.focus();
            return;
        }

        if (!validatePhoneInput(phoneValue)) {
            errorElement.textContent = 'Введите корректный номер телефона в формате: +7 (921) 123-45-67';
            errorElement.style.display = 'block';
            phoneInput.focus();
            return;
        }

        if (!validateNameInput(phoneValue)) {
            errorElement.textContent = 'Введите корректный номер телефона в формате: +7 (921) 123-45-67';
            errorElement.style.display = 'block';
            phoneInput.focus();
            return;
        }

        const formData = new FormData(form);

        fetch('send-form.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            console.log('Raw response:', text);

            try {
                const data = JSON.parse(text);
                if (data.success) {
                    alert(data.message);
                    form.reset();
                } else {
                    alert('Ошибка: ' + data.message);
                }
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Received text:', text);
                alert('Произошла ошибка обработки ответа сервера. Проверьте консоль для деталей.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка. Попробуйте ещё раз.');
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = document.querySelector('.close');

    const galleryImages = document.querySelectorAll('.gallery-image, .example-image');

    galleryImages.forEach(img => {
        img.addEventListener('click', function() {
            modalImg.src = this.src;
            modalImg.alt = this.alt;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });
    
    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
});
