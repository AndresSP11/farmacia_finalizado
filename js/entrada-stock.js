const valorInput = document.querySelector('#codigo_orden');

valorInput.addEventListener('input', (e) => {
    if (valorInput.value.length > 8) {
        valorInput.value = valorInput.value.slice(0, 8);
        e.preventDefault();
    }
});