{{-- <nav id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-white shadow-xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 border-r border-gray-200">
    <div class="p-6 h-full flex flex-col">
        <!-- Logo Section -->
        <div class="mb-8">
            <h1 class="text-xl font-display font-bold text-primary">Butik Solo Jala Buana</h1>
            <p class="text-xs text-gray-600 mt-1">ERP Management System</p>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="space-y-2 flex-1">
            <li>
                <a href="dashboard" 
                   class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300"
                   data-section="dashboard">
                   <i class='bx bx-home text-lg mr-3'></i>
                   <span class="font-medium">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="products" 
                   class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300"
                   data-section="products">
                   <i class='bx bx-package text-lg mr-3'></i>
                   <span class="font-medium">Products</span>
                </a>
            </li>
        </ul>
        
        <!-- User Section -->
        <div class="pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                    BS
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">Butik Solo</p>
                    <p class="text-xs text-gray-500 truncate">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu Button -->
<div class="lg:hidden fixed top-4 left-4 z-50">
    <button id="mobileMenuButton" class="p-2 bg-white rounded-lg shadow-lg">
        <i class='bx bx-menu text-xl'></i>
    </button>
</div>

<style>
.nav-item.active {
    background-color: #FEF7E6;
    color: #8B4513;
    border-left: 4px solid #D4AF37;
    font-weight: 600;
}

.nav-item.active i {
    color: #8B4513;
}

/* Smooth scrolling for the whole page */
html {
    scroll-behavior: smooth;
}

/* Section styling */
.page-section {
    min-height: 100vh;
    padding: 2rem;
}

/* Hide mobile menu when clicking on nav items on mobile */
@media (max-width: 1023px) {
    .nav-item {
        cursor: pointer;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const sidebar = document.getElementById('sidebar');
    const navItems = document.querySelectorAll('.nav-item');
    
    // Mobile menu toggle
    if (mobileMenuButton && sidebar) {
        mobileMenuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
    
    // Smooth scroll navigation
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                // Close mobile menu after click (on mobile devices)
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                }
                
                // Smooth scroll to section
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Update active state
                setActiveNavItem(targetId);
                
                // Update URL hash without scrolling
                history.pushState(null, null, `#${targetId}`);
            }
        });
    });
    
    // Function to set active navigation item
    function setActiveNavItem(sectionId) {
        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('data-section') === sectionId) {
                item.classList.add('active');
            }
        });
    }
    
    // Intersection Observer to detect which section is in view
    const sections = document.querySelectorAll('.page-section');
    
    if (sections.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.5
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const activeSection = entry.target.id;
                    setActiveNavItem(activeSection);
                }
            });
        }, observerOptions);
        
        sections.forEach(section => {
            observer.observe(section);
        });
    }
    
    // Set initial active state based on URL hash
    const initialHash = window.location.hash.substring(1);
    if (initialHash) {
        setActiveNavItem(initialHash);
    } else {
        // Default to dashboard if no hash
        setActiveNavItem('dashboard');
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const hash = window.location.hash.substring(1);
        if (hash) {
            setActiveNavItem(hash);
        }
    });
});
</script> --}}