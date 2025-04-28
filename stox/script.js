function handleGoogleSignIn() {
    // In a real app, this would use Google's OAuth API
    // For now, we'll simulate it
    const googleBtn = document.querySelector('.google-btn');
    googleBtn.innerHTML = '<div class="spinner"></div> Authenticating...';
    googleBtn.disabled = true;
    
    setTimeout(() => {
        window.location.href = 'google-auth.php?email=user@gmail.com&name=Google User';
    }, 1500);
}

// Add click handlers for terms links
document.querySelectorAll('.terms a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const term = this.textContent;
        alert(`Showing ${term} document. In a real app, this would open the actual document.`);
    });
});