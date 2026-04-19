        .aviso-privado {
            text-align: center; padding: 60px 20px;
            background: rgba(0,0,0,0.1); border: 1px dashed #444; border-radius: 8px;
            will-change: transform; touch-action: pan-y;
        }
        
        .card-post img, .card-post video {
            will-change: transform;
            transition: transform 0.3s ease;
        }
        .card-post:hover img { transform: scale(1.05); }