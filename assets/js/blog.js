document.addEventListener('DOMContentLoaded', () => {
    // Share functionality
    const shareButtons = document.querySelectorAll('.share-button');
    shareButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const url = window.location.href;
            const platform = button.dataset.platform;
            let shareUrl;

            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                    break;
                case 'twitter':
                    const title = document.title;
                    shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
                    break;
                case 'linkedin':
                    shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
                    break;
            }

            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });

    // Related posts slider
    const relatedPosts = document.querySelector('.related-posts');
    if (relatedPosts) {
        const posts = relatedPosts.querySelectorAll('.blog-card');
        let currentIndex = 0;

        const showPosts = () => {
            posts.forEach((post, index) => {
                post.style.display = index >= currentIndex && index < currentIndex + 3 ? 'block' : 'none';
            });
        };

        document.querySelector('.next-posts')?.addEventListener('click', () => {
            if (currentIndex + 3 < posts.length) {
                currentIndex++;
                showPosts();
            }
        });

        document.querySelector('.prev-posts')?.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                showPosts();
            }
        });

        showPosts();
    }
});