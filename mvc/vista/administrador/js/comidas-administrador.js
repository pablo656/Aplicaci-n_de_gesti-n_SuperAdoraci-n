document.addEventListener('DOMContentLoaded', function () {
    const inputImagen = document.getElementById('input_imagen');
    if (inputImagen) {
        inputImagen.addEventListener('change', function () {
            const preview = document.getElementById('preview');
            if (this.files && this.files[0]) {
                preview.src = URL.createObjectURL(this.files[0]);
            }
        });
    }
});
