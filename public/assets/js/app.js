// app.js - POS System JavaScript

// Toggle sidebar on mobile
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    //console.log('1. Hamburger element found:', hamburger); // Check if found

    if (hamburger) {
        hamburger.addEventListener('click', function(e) {
            e.preventDefault(); // Stops any weird link/page jump behavior
            //console.log('2. Hamburger CLICKED!');

            const sidebar = document.getElementById('sidebar');
            //console.log('3. Sidebar element:', sidebar);

            if (sidebar) {
                sidebar.classList.toggle('open');
                //console.log('4. Class "open" is now:', sidebar.classList.contains('open'));
                //console.log('5. Sidebar classes:', sidebar.className);
            } else {
                //console.error('6. Sidebar NOT FOUND! Check ID.');
            }
        });
    } else {
        //console.error('Hamburger NOT FOUND! Check the class name ".hamburger".');
    }
});

// Language switching
function switchLanguage() {
    fetch('?route=switch-lang', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(() => {
        // Fallback: reload anyway
        location.reload();
    });
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Confirm delete
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.querySelector('.hamburger');
    if (window.innerWidth <= 768) {
        if (sidebar && !sidebar.contains(event.target) && !hamburger?.contains(event.target)) {
            sidebar.classList.remove('open');
        }
    }
});

function toggleClearButton(input) {
    const wrapper = input.closest('.input-clear-wrapper');
    const btn = wrapper.querySelector('.clear-btn');
    if (input.value.length > 0) {
        btn.classList.add('show');
    } else {
        btn.classList.remove('show');
    }
}

function clearInput(btn) {
    const input = btn.closest('.input-clear-wrapper').querySelector('input');
    input.value = '';
    input.focus();
    btn.classList.remove('show');
    // Trigger any onchange/keyup events if needed
    if (input.onkeyup) input.onkeyup();
    if (input.onchange) input.onchange();
}