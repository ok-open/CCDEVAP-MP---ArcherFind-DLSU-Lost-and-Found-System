document.addEventListener("DOMContentLoaded", () => {
    const uploadBoxes = document.querySelectorAll(".upload-box");

    uploadBoxes.forEach(box => {
        const input = box.querySelector("input[type='file']");
        const text = box.querySelector(".upload-text");
        const addButton = box.closest('.upload-wrapper')?.querySelector("[data-action='add-images']");
        const removeButton = box.closest('.upload-wrapper')?.querySelector("[data-action='remove-images']");
        const removedIdsInput = box.querySelector("#removed_image_ids, input[name='removed_image_ids']");
        let container = box.querySelector('.preview-container');
        const selectedFiles = [];
        const removedImageIds = new Set();
        let existingImages = [];
        let removeMode = false;

        if (!input) return;

        if (container) {
            const existingMarkers = container.querySelectorAll('.existing-image-data');
            const markerImages = Array.from(existingMarkers)
                .map(marker => ({
                    image_id: Number(marker.dataset.imageId) || 0,
                    img_filepath: String(marker.dataset.imagePath || "")
                }))
                .filter(img => img.img_filepath);

            const existingThumbs = container.querySelectorAll('.existing-thumb[data-existing-id] .preview-thumb');
            const thumbImages = Array.from(existingThumbs)
                .map(img => {
                    const wrapper = img.closest('.existing-thumb[data-existing-id]');
                    return {
                        image_id: Number(wrapper?.dataset?.existingId) || 0,
                        img_filepath: String(img.getAttribute('src') || "")
                    };
                })
                .filter(img => img.img_filepath);

            existingImages = markerImages.length > 0 ? markerImages : thumbImages;
        }

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

        function syncRemovedImageIds() {
            if (!removedIdsInput) return;
            removedIdsInput.value = Array.from(removedImageIds).join(',');
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

            const visibleExisting = existingImages.filter(img => !removedImageIds.has(img.image_id));
            const allPreviewItems = [
                ...visibleExisting.map(img => ({ type: 'existing', value: img })),
                ...selectedFiles.map(file => ({ type: 'new', value: file }))
            ];

            if (allPreviewItems.length === 0) {
                if (text) text.style.display = 'block';
                return;
            }

            if (text) text.style.display = 'none';

            allPreviewItems.forEach((item, index) => {
                if (item.type === 'new' && !item.value.type.startsWith('image/')) return;

                const wrapper = document.createElement('div');
                wrapper.className = 'preview-thumb-wrapper';

                const img = document.createElement('img');
                img.className = 'preview-thumb';

                if (item.type === 'existing') {
                    img.src = item.value.img_filepath;
                } else {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(item.value);
                }

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

                        if (item.type === 'existing') {
                            if (item.value.image_id) {
                                removedImageIds.add(item.value.image_id);
                                syncRemovedImageIds();
                            }
                        } else {
                            const newIndex = visibleExisting.length > index
                                ? -1
                                : index - visibleExisting.length;
                            if (newIndex >= 0) {
                                selectedFiles.splice(newIndex, 1);
                            }
                        }

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

            const existingCount = existingImages.filter(img => !removedImageIds.has(img.image_id)).length;
            const maxNewFilesAllowed = Math.max(0, 4 - existingCount);
            const combinedFiles = [...selectedFiles, ...newFiles];
            const limitedFiles = combinedFiles.slice(0, maxNewFilesAllowed);

            if (combinedFiles.length > maxNewFilesAllowed) {
                showImageLimitMessage();
            }

            selectedFiles.splice(0, selectedFiles.length, ...limitedFiles);
            syncInputFiles();
            renderPreviews();
        }

        input.addEventListener('change', function () {
            addFiles(this.files);
        });

        if (addButton) {
            addButton.addEventListener('click', event => {
                event.preventDefault();
                event.stopPropagation();
                removeMode = false;
                if (removeButton) removeButton.classList.remove('is-active');
                // Reset native picker value before opening so selecting the same
                // file again still fires change, without clearing current FileList.
                input.value = '';
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
            removedImageIds.clear();
            syncRemovedImageIds();
            syncInputFiles();
            renderPreviews();
        };

        syncRemovedImageIds();
        renderPreviews();
    });
});