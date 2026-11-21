document.addEventListener("DOMContentLoaded", function () {

    // Buka/tutup sidebar
    const menuToggle = document.getElementById('menu-toggle');
    const wrapper = document.getElementById('wrapper');

    if (menuToggle && wrapper) {
        menuToggle.addEventListener('click', function () {
            wrapper.classList.toggle('toggled');
        });
    }

    // Konfirmasi sebelum menghapus data
    document.querySelectorAll('form[data-konfirmasi]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm(form.dataset.konfirmasi)) {
                e.preventDefault();
            }
        });
    });

    // Pratinjau foto sebelum diunggah
    const inputFoto = document.getElementById('jdw_foto');
    const preview = document.getElementById('preview-foto');
    if (inputFoto && preview) {
        inputFoto.addEventListener('change', function () {
            const file = inputFoto.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });
    }
});
