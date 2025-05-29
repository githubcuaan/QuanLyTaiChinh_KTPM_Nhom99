// Get all sections and navigation items
const sections = {
    'sumary': document.getElementById('sumary'),
    'thunhap': document.getElementById('thunhap'),
    'chitieu': document.getElementById('chitieu'),
    'jar-config-section': document.getElementById('jar-config-section')
};

const navItems = document.querySelectorAll('nav ul li');

// Add click event listeners to navigation items
navItems.forEach((item, index) => {
    item.addEventListener('click', () => {
        // Remove active class from all items
        navItems.forEach(navItem => navItem.classList.remove('active'));
        
        // Add active class to clicked item
        item.classList.add('active');
        
        // Hide all sections
        Object.values(sections).forEach(section => {
            if (section) {
                section.style.display = 'none';
            }
        });
        
        // Show the selected section
        const sectionIds = ['sumary', 'thunhap', 'chitieu', 'jar-config-section'];
        const selectedSection = sections[sectionIds[index]];
        if (selectedSection) {
            selectedSection.style.display = 'block';
        }
    });
});

// Set initial active state
navItems[0].classList.add('active'); 
