<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willy Player Digital</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: black;
        }
        #player-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        video, img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .html-slide {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            box-sizing: border-box;
        }
        .html-slide h1 {
            font-size: 5rem;
            margin-bottom: 2rem;
        }
        .html-slide p {
            font-size: 2.5rem;
        }
    </style>
</head>
<body>

<div id="player-container"></div>

<script>
    let playlist = [];
    let currentIndex = 0;
    let playlistHash = '';

    async function fetchPlaylist() {
        try {
            const response = await fetch('/player/playlist');
            const data = await response.json();
            
            // Simple check if playlist changed (naive hash)
            const newHash = JSON.stringify(data);
            if (newHash !== playlistHash) {
                console.log('Playlist updated');
                playlist = data;
                playlistHash = newHash;
                
                // If playlist was empty and now has items, start playing
                if (currentIndex >= playlist.length) {
                    currentIndex = 0;
                    playNext();
                }
            }
        } catch (error) {
            console.error('Error fetching playlist:', error);
        }
    }

    function renderSlide(slide) {
        const container = document.getElementById('player-container');
        container.innerHTML = '';

        if (slide.type === 'video') {
            const video = document.createElement('video');
            video.src = '/uploads/' + slide.content;
            video.autoplay = true;
            video.muted = true; // Autoplay requires muted usually
            video.playsInline = true;
            video.onended = () => {
                nextSlide();
            };
            video.onerror = () => {
                console.error('Video error, skipping');
                setTimeout(nextSlide, 1000);
            };
            container.appendChild(video);
        } else if (slide.type === 'image') {
            const img = document.createElement('img');
            img.src = '/uploads/' + slide.content;
            container.appendChild(img);
            setTimeout(nextSlide, slide.duration * 1000);
        } else if (slide.type === 'html') {
            const content = JSON.parse(slide.content);
            const div = document.createElement('div');
            div.className = 'html-slide';
            div.style.backgroundColor = content.bg_color;
            div.style.color = content.text_color;
            
            if (content.logo) {
                const logoImg = document.createElement('img');
                logoImg.src = '/uploads/' + content.logo;
                logoImg.style.maxWidth = '300px';
                logoImg.style.maxHeight = '200px';
                logoImg.style.marginBottom = '2rem';
                logoImg.style.objectFit = 'contain';
                div.appendChild(logoImg);
            }

            const h1 = document.createElement('h1');
            h1.textContent = content.headline;
            div.appendChild(h1);
            
            const p = document.createElement('p');
            p.textContent = content.body;
            div.appendChild(p);
            
            container.appendChild(div);
            setTimeout(nextSlide, slide.duration * 1000);
        }
    }

    function nextSlide() {
        currentIndex++;
        if (currentIndex >= playlist.length) {
            currentIndex = 0;
            // Refresh playlist on loop restart
            fetchPlaylist();
        }
        playNext();
    }

    function playNext() {
        if (playlist.length === 0) {
            // Retry fetching if empty
            setTimeout(async () => {
                await fetchPlaylist();
                playNext();
            }, 5000);
            return;
        }
        
        if (currentIndex < playlist.length) {
            renderSlide(playlist[currentIndex]);
        } else {
            currentIndex = 0;
            renderSlide(playlist[currentIndex]);
        }
    }

    // Initial load
    fetchPlaylist().then(() => {
        if (playlist.length > 0) {
            playNext();
        } else {
            // Poll until content exists
            const poller = setInterval(async () => {
                await fetchPlaylist();
                if (playlist.length > 0) {
                    clearInterval(poller);
                    playNext();
                }
            }, 5000);
        }
    });

</script>

</body>
</html>
