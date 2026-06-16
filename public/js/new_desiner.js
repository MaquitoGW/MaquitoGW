document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    const currentYear = document.getElementById('current-year');
    if (currentYear) {
      currentYear.textContent = new Date().getFullYear();
    }
    
    // Header scroll effect
    const header = document.querySelector('.header');
    window.addEventListener('scroll', function() {
      if (window.scrollY > 10) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    });
  
    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileNav = document.querySelector('.mobile-nav');
    
    if (menuToggle && mobileNav) {
      menuToggle.addEventListener('click', function() {
        mobileNav.classList.toggle('active');
        
        // Change icon
        const icon = menuToggle.querySelector('i');
        if (mobileNav.classList.contains('active')) {
          icon.classList.remove('fa-bars');
          icon.classList.add('fa-times');
        } else {
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      });
    }
  
    // Close mobile menu when clicking on a link
    const mobileLinks = document.querySelectorAll('.nav-mobile .nav-link');
    mobileLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (mobileNav && menuToggle) {
          mobileNav.classList.remove('active');
          const icon = menuToggle.querySelector('i');
          icon.classList.remove('fa-times');
          icon.classList.add('fa-bars');
        }
      });
    });
  
    // Hero canvas animation
    const canvas = document.getElementById('hero-canvas');
    if (canvas) {
      const ctx = canvas.getContext('2d');
      let particles = [];
      
      function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        initParticles();
      }
      
      class Particle {
        constructor() {
          this.x = Math.random() * canvas.width;
          this.y = Math.random() * canvas.height;
          this.size = Math.random() * 3 + 1;
          this.speedX = (Math.random() - 0.5) * 0.5;
          this.speedY = (Math.random() - 0.5) * 0.5;
          
          // Get primary color from CSS variable
          const isDark = document.documentElement.classList.contains('dark');
          const primaryRgb = getComputedStyle(document.documentElement)
            .getPropertyValue(isDark ? '--primary-rgb' : '--primary-rgb')
            .trim();
          
          this.color = `rgba(${primaryRgb}, ${Math.random() * 0.3 + 0.1})`;
        }
        
        update() {
          this.x += this.speedX;
          this.y += this.speedY;
          
          if (this.x > canvas.width) this.x = 0;
          else if (this.x < 0) this.x = canvas.width;
          if (this.y > canvas.height) this.y = 0;
          else if (this.y < 0) this.y = canvas.height;
        }
        
        draw() {
          ctx.fillStyle = this.color;
          ctx.beginPath();
          ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
          ctx.fill();
        }
      }
      
      function initParticles() {
        particles = [];
        const particleCount = Math.min(
          Math.floor((canvas.width * canvas.height) / 10000),
          100
        );
        for (let i = 0; i < particleCount; i++) {
          particles.push(new Particle());
        }
      }
      
      function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(particle => {
          particle.update();
          particle.draw();
        });
        requestAnimationFrame(animate);
      }
      
      window.addEventListener('resize', resizeCanvas);
      resizeCanvas();
      animate();
    }
  
    // Project filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    
    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        const filter = this.getAttribute('data-filter');
        
        // Show/hide projects based on filter
        projectCards.forEach(card => {
          if (filter === 'all') {
            card.style.display = 'block';
          } else {
            const tags = card.getAttribute('data-tags').split(',');
            if (tags.includes(filter)) {
              card.style.display = 'block';
            } else {
              card.style.display = 'none';
            }
          }
        });
      });
    });
  
    // Project modal
    const modal = document.getElementById('project-modal');
    const viewButtons = document.querySelectorAll('.view-project');
    const modalClose = document.querySelector('.modal-close');
    
    viewButtons.forEach((button, index) => {
      button.addEventListener('click', function() {
        const project = projectsData[index];
        if (!project || !modal) return;
        
        // Populate modal content
        const imageElement = document.querySelector('.modal-image img');
        const galleryContainer = document.querySelector('.modal-gallery');
        const videoContainer = document.querySelector('.modal-video');
        const descriptionContainer = document.querySelector('.modal-description');
        const projectImages = project.images && project.images.length ? project.images : [project.image];
        const labels = window.minimalistTranslations || {};

        document.querySelector('.modal-title').textContent = project.title;
        imageElement.src = project.image;
        imageElement.alt = project.title;
        descriptionContainer.innerHTML = project.longDescription || project.description || '';

        galleryContainer.innerHTML = '';
        projectImages.forEach((image, imageIndex) => {
          const thumb = document.createElement('button');
          thumb.type = 'button';
          thumb.className = `modal-thumb${imageIndex === 0 ? ' active' : ''}`;
          thumb.setAttribute('aria-label', `${labels.viewImage || 'View image'} ${imageIndex + 1}`);
          thumb.innerHTML = `<img src="${image}" alt="${project.title} ${imageIndex + 1}">`;
          thumb.addEventListener('click', function() {
            imageElement.src = image;
            galleryContainer.querySelectorAll('.modal-thumb').forEach(item => item.classList.remove('active'));
            thumb.classList.add('active');
          });
          galleryContainer.appendChild(thumb);
        });

        videoContainer.innerHTML = '';
        if (project.video) {
          const video = document.createElement('video');
          video.controls = true;
          video.preload = 'metadata';
          video.src = project.video;
          videoContainer.appendChild(video);
        }
        
        // Clear and populate tags
        const tagsContainer = document.querySelector('.modal-tags');
        tagsContainer.innerHTML = '';
        project.tags.forEach(tag => {
          const tagSpan = document.createElement('span');
          tagSpan.className = 'tag';
          tagSpan.textContent = tag;
          tagsContainer.appendChild(tagSpan);
        });
        
        // Clear and populate links
        const linksContainer = document.querySelector('.modal-links');
        linksContainer.innerHTML = '';
        
        if (project.github) {
          const githubLink = document.createElement('a');
          githubLink.href = project.github;
          githubLink.target = '_blank';
          githubLink.rel = 'noopener noreferrer';
          githubLink.className = 'btn btn-outline';
          githubLink.innerHTML = `<i class="fab fa-github"></i> ${labels.viewCode || 'View Code'}`;
          linksContainer.appendChild(githubLink);
        }
        
        if (project.demo) {
          const demoLink = document.createElement('a');
          demoLink.href = project.demo;
          demoLink.target = '_blank';
          demoLink.rel = 'noopener noreferrer';
          demoLink.className = 'btn btn-primary';
          demoLink.innerHTML = `<i class="fas fa-external-link-alt"></i> ${labels.liveDemo || 'Live Demo'}`;
          linksContainer.appendChild(demoLink);
        }
        
        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
      });
    });
    
    // Close modal
    if (modalClose) {
      modalClose.addEventListener('click', function() {
        modal.classList.remove('active');
        document.body.style.overflow = ''; // Re-enable scrolling
      });
    }
    
    // Close modal when clicking outside
    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          modal.classList.remove('active');
          document.body.style.overflow = '';
        }
      });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
      if (modal && e.key === 'Escape' && modal.classList.contains('active')) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  
    // Contact form submission
    // Contact form uses the Laravel action configured in the template.
    const contactFeedback = window.minimalistContactFeedback || {};
    const translations = window.minimalistTranslations || {};
    const contactToast = document.getElementById('contact-toast');

    function showContactToast(type, message, errors = []) {
      if (!contactToast) return;

      const icon = contactToast.querySelector('.site-toast-icon i');
      const title = contactToast.querySelector('.site-toast-title');
      const messageElement = contactToast.querySelector('.site-toast-message');
      const list = contactToast.querySelector('.site-toast-list');

      contactToast.classList.toggle('is-error', type === 'error');
      icon.className = type === 'error' ? 'fas fa-circle-exclamation' : 'fas fa-circle-check';
      title.textContent = type === 'error'
        ? (translations.contactErrorTitle || 'Review your contact details')
        : (translations.contactSuccessTitle || 'Contact sent');
      messageElement.textContent = message || '';
      list.innerHTML = '';

      errors.forEach((error) => {
        const item = document.createElement('li');
        item.textContent = error;
        list.appendChild(item);
      });

      contactToast.classList.add('active');
      contactToast.setAttribute('aria-hidden', 'false');
    }

    const closeContactToast = contactToast?.querySelector('.site-toast-close');
    closeContactToast?.addEventListener('click', function() {
      contactToast.classList.remove('active');
      contactToast.setAttribute('aria-hidden', 'true');
    });

    if (contactFeedback.success) {
      showContactToast('success', contactFeedback.success);
    } else if (Array.isArray(contactFeedback.errors) && contactFeedback.errors.length > 0) {
      showContactToast('error', '', contactFeedback.errors);
      document.getElementById('contact')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    const cookiePopup = document.getElementById('cookie-popup');
    const cookieAccept = document.getElementById('cookie-accept');
    const cookieStorageKey = 'minimalist-cookie-consent';

    if (cookiePopup && localStorage.getItem(cookieStorageKey) !== 'accepted') {
      window.setTimeout(() => {
        if (contactToast?.classList.contains('active')) {
          window.setTimeout(() => {
            cookiePopup.classList.add('active');
            cookiePopup.setAttribute('aria-hidden', 'false');
          }, 3200);
          return;
        }

        cookiePopup.classList.add('active');
        cookiePopup.setAttribute('aria-hidden', 'false');
      }, 700);
    }

    cookieAccept?.addEventListener('click', function() {
      localStorage.setItem(cookieStorageKey, 'accepted');
      cookiePopup?.classList.remove('active');
      cookiePopup?.setAttribute('aria-hidden', 'true');
    });

    // Back to top
    const backToTop = document.querySelector('.back-to-top');
    if (backToTop) {
      window.addEventListener('scroll', function() {
        backToTop.classList.toggle('visible', window.scrollY > 500);
      });

      backToTop.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  
    // Animate elements when they come into view
    function animateOnScroll() {
      const elements = document.querySelectorAll('.section-header, .about-image, .about-text, .project-card, .skill-category, .experience-item, .contact-info, .contact-form-container');
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1 });
      
      elements.forEach(element => {
        observer.observe(element);
      });
    }
    
    // Initialize animations
    animateOnScroll();
  });
