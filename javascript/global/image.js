document.addEventListener("DOMContentLoaded", () => {
    const uploadBoxes = document.querySelectorAll(".upload-box");

    uploadBoxes.forEach(box => {
        const input = box.querySelector("input[type='file']");
        const text = box.querySelector(".upload-text");
        const addButton = box.closest('.upload-wrapper')?.querySelector("[data-action='add-images']");
        const removeButton = box.closest('.upload-wrapper')?.querySelector("[data-action='remove-images']");
        let container = box.querySelector('.preview-container');
        const selectedFiles = [];
        let removeMode = false;

        if (!container) {
            container = document.createElement('div');
            container.className = 'preview-container';
            const existingImg = box.querySelector('.preview-image');
            if (existingImg) {
                existingImg.parentNode.replaceChild(container, existingImg);
            } else {
                box.appendChild(container);
            }
        }

        function syncInputFiles() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            input.files = dt.files;
        }

        function showImageLimitMessage() {
            if (typeof showToast === 'function') {
                showToast('You can upload only up to 4 images', 'var(--color-errorMsg)', 4000);
            } else {
                alert('You can upload only up to 4 images');
            }
        }

        function renderPreviews() {
            container.innerHTML = '';

            if (selectedFiles.length === 0) {
                if (text) text.style.display = 'block';
                return;
            }

            if (text) text.style.display = 'none';

            selectedFiles.forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;

                const wrapper = document.createElement('div');
                wrapper.className = 'preview-thumb-wrapper';

                const img = document.createElement('img');
                img.className = 'preview-thumb';

                const reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);

                wrapper.appendChild(img);

                if (removeMode) {
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'preview-remove-btn';
                    removeBtn.textContent = '×';
                    removeBtn.setAttribute('aria-label', 'Remove image');
                    removeBtn.title = 'Remove image';
                    removeBtn.addEventListener('click', event => {
                        event.preventDefault();
                        event.stopPropagation();
                        selectedFiles.splice(index, 1);
                        syncInputFiles();
                        renderPreviews();
                    });
                    wrapper.appendChild(removeBtn);
                }

                container.appendChild(wrapper);
            });
        }

        function addFiles(files) {
            const newFiles = Array.from(files || []);
            if (newFiles.length === 0) return;

            const combinedFiles = [...selectedFiles, ...newFiles];
            const limitedFiles = combinedFiles.slice(0, 4);

            if (combinedFiles.length > 4) {
                showImageLimitMessage();
            }

            selectedFiles.splice(0, selectedFiles.length, ...limitedFiles);
            syncInputFiles();
            renderPreviews();
        }

        input.addEventListener('change', function () {
            addFiles(this.files);
            this.value = '';
        });

        if (addButton) {
            addButton.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                removeMode = false;
                if (removeButton) removeButton.classList.remove('is-active');
                input.click();
            });
        }

        if (removeButton) {
            removeButton.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                removeMode = !removeMode;
                removeButton.classList.toggle('is-active', removeMode);
                renderPreviews();
            });
        }

        box.resetPreview = function () {
            removeMode = false;
            if (removeButton) removeButton.classList.remove('is-active');
            selectedFiles.splice(0, selectedFiles.length);
            syncInputFiles();
            renderPreviews();
        };
    });
});