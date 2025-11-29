// Function to show selected page and hide others
function showPage(pageId) {
    // Hide all pages
    const pages = document.querySelectorAll('.page');
    pages.forEach(page => {
        page.classList.remove('active');
    });
    
    // Remove active class from all nav links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected page
    const selectedPage = document.getElementById(pageId);
    if (selectedPage) {
        selectedPage.classList.add('active');
    }
    
    // Add active class to clicked nav link
    event.target.classList.add('active');
    
    // Animate proficiency bars on biodata page
    if (pageId === 'biodata') {
        setTimeout(() => {
            const proficiencyBars = document.querySelectorAll('.proficiency-fill');
            proficiencyBars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                bar.style.width = width + '%';
            });
        }, 100);
    }
}

// Initialize the page when document loads
window.addEventListener('load', () => {
    // Set all proficiency bars to 0% initially
    const proficiencyBars = document.querySelectorAll('.proficiency-fill');
    proficiencyBars.forEach(bar => {
        bar.style.width = '0%';
    });
    
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
        });
    });
});

// Optional: Add keyboard navigation
document.addEventListener('keydown', (e) => {
    const pages = ['home', 'resume', 'biodata'];
    const currentPage = document.querySelector('.page.active').id;
    const currentIndex = pages.indexOf(currentPage);
    
    // Arrow right - next page
    if (e.key === 'ArrowRight' && currentIndex < pages.length - 1) {
        const nextPage = pages[currentIndex + 1];
        const nextLink = document.querySelector(`a[onclick="showPage('${nextPage}')"]`);
        if (nextLink) nextLink.click();
    }
    
    // Arrow left - previous page
    if (e.key === 'ArrowLeft' && currentIndex > 0) {
        const prevPage = pages[currentIndex - 1];
        const prevLink = document.querySelector(`a[onclick="showPage('${prevPage}')"]`);
        if (prevLink) prevLink.click();
    }
});

// Add animation to skill tags on hover
document.addEventListener('DOMContentLoaded', () => {
    const skillTags = document.querySelectorAll('.skill-tag');
    skillTags.forEach(tag => {
        tag.addEventListener('mouseenter', function() {
            this.style.cursor = 'pointer';
        });
    });
    
    // Add hover effect to experience items
    const experienceItems = document.querySelectorAll('.experience-item, .education-item');
    experienceItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.cursor = 'pointer';
        });
    });
});