document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            const filterValue = button.getAttribute('data-filter');

            // Show all items if filter is '*'
            if (filterValue === '*') {
                galleryItems.forEach(item => {
                    item.style.display = 'block';
                });
            } else {
                // Hide all items first
                galleryItems.forEach(item => {
                    item.style.display = 'none';
                });
                // Show items matching the filter
                document.querySelectorAll(filterValue).forEach(item => {
                    item.style.display = 'block';
                });
            }

            // Remove 'active' class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Add 'active' class to the clicked button
            button.classList.add('active');
        });
    });
});
