setTimeout(() => {
        const video = document.getElementById('logoVideo');

        video.playbackRate = 0.75; // 75% speed

        let fading = false;

        video.addEventListener('timeupdate', () => {
            if (fading) return;

            const remaining = video.duration - video.currentTime;

            if (video.duration > 0 && remaining <= 0.5) {
                fading = true;

                video.pause();

                video.style.transition = 'opacity 5s linear';
                video.style.opacity = '0.25';
            }
        });

    }, 2000); // wait 3 seconds         