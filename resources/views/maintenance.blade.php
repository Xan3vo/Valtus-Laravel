<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Valtus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            overflow: hidden;
        }
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%, #f8fafc 100%);
            background-size: 400% 400%;
            animation: gradientFlow 15s ease infinite;
            position: relative;
        }
        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes float3D {
            0%, 100% { 
                transform: translateY(0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1);
            }
            25% { 
                transform: translateY(-20px) rotateX(5deg) rotateY(-5deg) rotateZ(2deg) scale(1.05);
            }
            50% { 
                transform: translateY(-35px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1.1);
            }
            75% { 
                transform: translateY(-20px) rotateX(-5deg) rotateY(5deg) rotateZ(-2deg) scale(1.05);
            }
        }
        .image-wrapper {
            transform: translate(calc(var(--mouse-x, 0px)), calc(var(--mouse-y, 0px)));
            transition: transform 0.1s ease-out;
        }
        .float-3d {
            animation: float3D 6s ease-in-out infinite;
            transform-style: preserve-3d;
        }
        @keyframes rotateGlow {
            0% {
                filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.3)) drop-shadow(0 0 40px rgba(59, 130, 246, 0.2));
            }
            25% {
                filter: drop-shadow(0 0 30px rgba(147, 51, 234, 0.3)) drop-shadow(0 0 50px rgba(147, 51, 234, 0.2));
            }
            50% {
                filter: drop-shadow(0 0 20px rgba(236, 72, 153, 0.3)) drop-shadow(0 0 40px rgba(236, 72, 153, 0.2));
            }
            75% {
                filter: drop-shadow(0 0 30px rgba(34, 197, 94, 0.3)) drop-shadow(0 0 50px rgba(34, 197, 94, 0.2));
            }
            100% {
                filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.3)) drop-shadow(0 0 40px rgba(59, 130, 246, 0.2));
            }
        }
        .glow-rotate {
            animation: rotateGlow 8s ease-in-out infinite;
        }
        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-100px) translateX(50px) rotate(180deg);
                opacity: 0.8;
            }
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat 8s ease-in-out infinite;
        }
        @keyframes pulseRing {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        .pulse-ring {
            position: absolute;
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            animation: pulseRing 3s ease-out infinite;
        }
    </style>
</head>
<body class="flex items-center justify-center">
    <div class="relative w-full h-full flex items-center justify-center">
        <!-- Floating Particles Background -->
        <div id="particlesContainer"></div>
        
        <!-- Pulse Rings -->
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="pulse-ring" style="width: 300px; height: 300px; animation-delay: 0s;"></div>
            <div class="pulse-ring" style="width: 300px; height: 300px; animation-delay: 1s;"></div>
            <div class="pulse-ring" style="width: 300px; height: 300px; animation-delay: 2s;"></div>
        </div>
        
        <!-- Maintenance Image -->
        <div class="relative z-10 image-wrapper" id="imageWrapper">
            <img 
                src="{{ asset('assets/images/maintenance.png') }}" 
                alt="Maintenance" 
                class="w-64 h-64 sm:w-80 sm:h-80 md:w-96 md:h-96 lg:w-[450px] lg:h-[450px] object-contain float-3d glow-rotate"
                id="maintenanceImage"
            >
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const image = document.getElementById('maintenanceImage');
            const imageWrapper = document.getElementById('imageWrapper');
            const particlesContainer = document.getElementById('particlesContainer');
            
            // Create floating particles
            function createParticle() {
                const particle = document.createElement('div');
                const size = Math.random() * 8 + 4;
                const colors = [
                    'rgba(59, 130, 246, 0.4)',   // blue
                    'rgba(147, 51, 234, 0.4)',  // purple
                    'rgba(236, 72, 153, 0.4)',  // pink
                    'rgba(34, 197, 94, 0.4)',   // green
                    'rgba(251, 146, 60, 0.4)'   // orange
                ];
                
                particle.className = 'particle';
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 6) + 's';
                
                particlesContainer.appendChild(particle);
            }
            
            // Create initial particles
            for (let i = 0; i < 15; i++) {
                createParticle();
            }
            
            // Interactive mouse parallax (subtle, works with CSS float animation)
            let mouseX = 0;
            let mouseY = 0;
            
            document.addEventListener('mousemove', function(e) {
                mouseX = (e.clientX / window.innerWidth - 0.5) * 15;
                mouseY = (e.clientY / window.innerHeight - 0.5) * 15;
                
                // Apply subtle parallax using CSS custom properties on wrapper
                imageWrapper.style.setProperty('--mouse-x', mouseX + 'px');
                imageWrapper.style.setProperty('--mouse-y', mouseY + 'px');
            });
            
            // Add CSS variable support for parallax
            imageWrapper.style.setProperty('--mouse-x', '0px');
            imageWrapper.style.setProperty('--mouse-y', '0px');
            
            // Hover effect
            image.addEventListener('mouseenter', function() {
                this.style.transition = 'filter 0.3s ease, transform 0.3s ease';
                this.style.filter = 'brightness(1.15) saturate(1.2)';
            });
            
            image.addEventListener('mouseleave', function() {
                this.style.transition = 'filter 0.3s ease';
                this.style.filter = 'brightness(1) saturate(1)';
            });
            
            // Prevent scrolling
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
        });
    </script>
</body>
</html>

