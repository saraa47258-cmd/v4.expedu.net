// Custom JavaScript for Expedu Website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initNavbar();
    initSmoothScrolling();
    initContactForm();
    initAnimations();
    initCounters();
    initTooltips();
});

// Navbar functionality
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.nav-link');
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Active link highlighting
    window.addEventListener('scroll', function() {
        let current = '';
        const sections = document.querySelectorAll('section[id]');
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollY >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
    
    // Mobile menu close on link click
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            const navbarCollapse = document.querySelector('.navbar-collapse');
            if (navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                bsCollapse.hide();
            }
        });
    });
}

// Smooth scrolling for anchor links
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            // Skip empty or special anchors
            if (!targetId || targetId === '#' || targetId === '#!' || targetId.length < 2) return;
            
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                e.preventDefault();
                const offsetTop = targetSection.offsetTop - 80; // Account for fixed navbar
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Contact form handling
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        // Only bind if no existing handler (avoid double-binding on index.php)
        if (contactForm.dataset.handlerBound) return;
        contactForm.dataset.handlerBound = 'true';
        
        // Real-time validation
        const inputs = contactForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
    }
}

// Field validation
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let message = '';
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        message = 'هذا الحقل مطلوب';
    }
    
    // Email validation
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            message = 'يرجى إدخال بريد إلكتروني صحيح';
        }
    }
    
    // Phone validation
    if (field.type === 'tel' && value) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{8,}$/;
        if (!phoneRegex.test(value)) {
            isValid = false;
            message = 'يرجى إدخال رقم هاتف صحيح';
        }
    }
    
    // Update field appearance
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        removeFieldError(field);
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        showFieldError(field, message);
    }
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    removeFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

// Remove field error
function removeFieldError(field) {
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
}

// Show success/error messages
function showMessage(message, type) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.alert-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Create new message
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show alert-message`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert message
    const contactForm = document.getElementById('contactForm');
    contactForm.parentNode.insertBefore(alertDiv, contactForm);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
    
    // Scroll to message
    alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Initialize animations
function initAnimations() {
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.service-card, .team-card, .stat-card, .value-card');
    animatedElements.forEach(el => {
        observer.observe(el);
    });
}

// Initialize counters
function initCounters() {
    // Enhanced counters for new design
    const enhancedCounters = document.querySelectorAll('.counter-container[data-count]');
    const oldCounters = document.querySelectorAll('.stat-card .h2, .stat-card .h3');
    
    const counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.hasAttribute('data-count')) {
                    animateEnhancedCounter(entry.target);
                } else {
                    animateCounter(entry.target);
                }
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    // Observe both old and new counters
    [...enhancedCounters, ...oldCounters].forEach(counter => {
        counterObserver.observe(counter);
    });
}

// Animate enhanced counter
function animateEnhancedCounter(element) {
    const targetCount = parseInt(element.getAttribute('data-count'));
    if (isNaN(targetCount)) return;
    
    const duration = 2500;
    const steps = 100;
    const increment = targetCount / steps;
    let current = 0;
    
    // Add pulse effect during animation
    element.style.animation = 'pulseGlow 0.5s ease-in-out infinite';
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= targetCount) {
            current = targetCount;
            clearInterval(timer);
            element.style.animation = '';
            element.classList.add('counter-completed');
        }
        
        element.textContent = Math.floor(current);
        
        // Add special effects at certain milestones
        if (Math.floor(current) % Math.floor(targetCount / 4) === 0 && current > 0) {
            element.style.transform = 'scale(1.1)';
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 150);
        }
    }, duration / steps);
}

// Animate counter (legacy)
function animateCounter(element) {
    const text = element.textContent;
    const number = parseInt(text.replace(/\D/g, ''));
    const suffix = text.replace(/[\d\s]/g, '');
    
    if (isNaN(number)) return;
    
    const duration = 2000;
    const steps = 60;
    const increment = number / steps;
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= number) {
            current = number;
            clearInterval(timer);
        }
        
        element.textContent = Math.floor(current) + suffix;
    }, duration / steps);
}

// Initialize tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// WhatsApp integration
function openWhatsApp(message = '') {
    const phoneNumber = '96898992443';
    const defaultMessage = 'مرحباً، أرغب في الاستفسار عن خدماتكم .';
    const finalMessage = message || defaultMessage;
    const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(finalMessage)}`;
    window.open(url, '_blank');
}

// Add WhatsApp click handlers
document.addEventListener('DOMContentLoaded', function() {
    const whatsappLinks = document.querySelectorAll('a[href*="wa.me"], .btn-whatsapp');
    whatsappLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            openWhatsApp();
        });
    });
});

// Service request handling
function requestService(serviceName) {
    const message = `مرحباً، أرغب في الاستفسار عن خدمة: ${serviceName}`;
    openWhatsApp(message);
}

// Add service button handlers
document.addEventListener('DOMContentLoaded', function() {
    const serviceButtons = document.querySelectorAll('.service-card .btn');
    serviceButtons.forEach(button => {
        button.addEventListener('click', function() {
            const serviceCard = this.closest('.service-card');
            const serviceName = serviceCard.querySelector('h5').textContent;
            requestService(serviceName);
        });
    });
});

// Back to top button
function initBackToTop() {
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.className = 'btn btn-primary btn-back-to-top';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: none;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    document.body.appendChild(backToTopBtn);
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Initialize back to top button
document.addEventListener('DOMContentLoaded', initBackToTop);

// Performance optimization
window.addEventListener('load', function() {
    // Remove loading states
    document.body.classList.remove('loading');
    
    // Lazy load images if any
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});

// Ads Section functionality
function initAdsSection() {
    const adsCards = document.querySelectorAll('.ads-card');
    const adsButtons = document.querySelectorAll('.ads-content .btn');
    
    // Add click tracking for ads buttons
    adsButtons.forEach((button, index) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            // Track click (you can add analytics here)
            console.log(`Ads button ${index + 1} clicked`);
            
            // Show different modals based on button
            showAdsModal(index + 1);
        });
    });
    
    // Add intersection observer for ads visibility with staggered animation
    const adsObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 200); // Staggered animation
            }
        });
    }, {
        threshold: 0.1
    });
    
    // Set initial state and observe each card
    adsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        adsObserver.observe(card);
    });
    
    // Add hover effects for better interaction
    adsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Show ads modal based on which ad was clicked
function showAdsModal(adNumber) {
    let message = '';
    
    switch(adNumber) {
        case 1:
            message = '🎉 عرض خاص - خصم 30% على جميع الدورات!\n\n📚 احصل على خصم حصري على:\n• الدورات الأكاديمية\n• ورش التطوير المهني\n• الاستشارات \n\n📞 للحجز: +968 92332749';
            break;
        case 2:
            message = '🚀 دورة التطوير المهني الجديدة!\n\n💼 انضم إلى دورة متخصصة في:\n• بناء المهارات المهنية\n• تطوير الذات\n• التخطيط الوظيفي\n\n📱 للحجز: +968 96988101';
            break;
        case 3:
            message = '🎯 استشارة مجانية مع خبرائنا!\n\n👨‍🏫 احجز استشارة مجانية مع:\n• خبراء أكاديميون معتمدون\n• استشارة مخصصة لحالتك\n• خطة  شاملة\n\n📧 للحجز: info@expedu.net';
            break;
        default:
            message = 'شكراً لاهتمامك!\n\nيمكنك التواصل معنا عبر:\n📞 +968 92332749\n📱 +968 96988101\n📧 info@expedu.net';
    }
    
    alert(message);
}

// Initialize ads section when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initAdsSection();
});

