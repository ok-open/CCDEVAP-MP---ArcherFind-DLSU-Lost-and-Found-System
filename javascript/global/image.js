document.addEventListener("DOMContentLoaded", () => {
    const uploadBoxes = document.querySelectorAll(".upload-box");

    uploadBoxes.forEach(box => {
        const input = box.querySelector("input[type='file']");
        const text = box.querySelector(".upload-text");
        let container = box.querySelector('.preview-container');

        if (!container) {
            // fallback to single image preview for backward compatibility
            container = document.createElement('div');
            container.className = 'preview-container';
            const existingImg = box.querySelector('.preview-image');
            if (existingImg) {
                existingImg.parentNode.replaceChild(container, existingImg);
            } else {
                box.appendChild(container);
            }
        }

        input.addEventListener('change', function () {
            // Clear previous previews
            container.innerHTML = '';

            let filesArr = Array.from(this.files || []);

            if (filesArr.length === 0) {
                text.style.display = 'block';
                return;
            }

            // Enforce client-side limit of 4 files
            if (filesArr.length > 4) {
                if (typeof showToast === 'function') {
                    showToast('You can upload only up to 4 images', 'var(--color-errorMsg)', 4000);
                } else {
                    alert('You can upload only up to 4 images');
                }

                // Keep only the first 4 files in the input using DataTransfer
                const dt = new DataTransfer();
                filesArr.slice(0, 4).forEach(f => dt.items.add(f));
                this.files = dt.files;
                filesArr = Array.from(this.files);
            }

            text.style.display = 'none';

            filesArr.slice(0, 4).forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                const img = document.createElement('img');
                img.className = 'preview-thumb';

                reader.onload = function (e) {
                    img.src = e.target.result;
                };

                reader.readAsDataURL(file);
                container.appendChild(img);
            });
        });

        box.resetPreview = function () {
            input.value = '';
            container.innerHTML = '';
            if (text) text.style.display = 'block';
        };
    });
});