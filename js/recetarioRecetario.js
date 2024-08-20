const CantidadPet = document.getElementById('CodigoAlumno');
CantidadPet.addEventListener('input', function () {
    if (this.value < 0) {
        this.value = 0; // Reset to 0 if negative value is inputted
    }
});

CantidadPet.addEventListener('keydown', function (event) {
    if (event.key === '-' || event.key === 'e') {
        event.preventDefault(); // Prevent the user from entering the minus sign or 'e'
    }
});

const fichadorReceta = document.getElementById('fichador-input');
fichadorReceta.addEventListener('input', function () {
    if (this.value < 0) {
        this.value = 0; // Reset to 0 if negative value is inputted
    }
});

fichadorReceta.addEventListener('keydown', function (event) {
    if (event.key === '-' || event.key === 'e') {
        event.preventDefault(); // Prevent the user from entering the minus sign or 'e'
    }
});
