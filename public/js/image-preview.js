(() => {
    document.querySelectorAll('input[type="file"][accept^="image/"]').forEach((input) => {
        const grid = input.closest('section')?.querySelector('[data-preview-grid]') || document.createElement('div');
        grid.classList.add('image-preview-grid');
        if (!grid.isConnected) input.closest('label').after(grid);
        let urls = [];

        const render = () => {
            urls.forEach((url) => URL.revokeObjectURL(url));
            urls = [];
            grid.replaceChildren();
            Array.from(input.files).forEach((file, index) => {
                const item = document.createElement('div');
                item.className = 'preview-item';
                const image = document.createElement('img');
                image.alt = file.name;
                image.src = URL.createObjectURL(file);
                urls.push(image.src);
                item.append(image);
                const remove = document.createElement('button');
                remove.type = 'button';
                remove.textContent = '×';
                remove.setAttribute('aria-label', `Remove ${file.name}`);
                remove.addEventListener('click', () => {
                    try {
                        const transfer = new DataTransfer();
                        Array.from(input.files).forEach((selected, selectedIndex) => {
                            if (selectedIndex !== index) transfer.items.add(selected);
                        });
                        input.files = transfer.files;
                    } catch {
                        // Older browsers cannot edit FileList; clear the selection instead.
                        input.value = '';
                    }
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
                item.append(remove);
                grid.append(item);
            });
        };

        // Banner and profile forms already provide their own single-image preview.
        if (input.matches('[data-banner-upload], [data-profile-image]')) return;
        input.addEventListener('change', render);
        input.form?.addEventListener('reset', () => setTimeout(render, 0));
        window.addEventListener('pagehide', () => urls.forEach((url) => URL.revokeObjectURL(url)));
    });
})();
