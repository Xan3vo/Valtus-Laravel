@extends('layouts.app')

@section('title', 'Admin • Media Management')

@section('body')
@include('admin.partials.navigation')

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="break-words">Media Management</span>
        </h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">Kelola foto dan video untuk halaman website</p>
        
         <!-- Instructions -->
         <div class="mt-4 p-4 bg-blue-600/20 border border-blue-500/30 rounded-lg">
             <h3 class="text-blue-300 font-semibold text-sm mb-2">📋 Cara Menggunakan Preview:</h3>
             <ul class="text-blue-200 text-xs space-y-1">
                 <li>• <strong>Upload File:</strong> Drag & drop atau klik area upload, preview akan muncul otomatis</li>
                 <li>• <strong>URL Gambar:</strong> Masukkan link gambar (JPG, PNG, GIF), preview muncul otomatis setelah 1 detik</li>
                 <li>• <strong>URL Video:</strong> Masukkan link YouTube, preview muncul otomatis setelah 1 detik</li>
                 <li>• <strong>YouTube:</strong> Gunakan link: https://www.youtube.com/watch?v=VIDEO_ID</li>
                 <li>• <strong>Contoh Link:</strong> https://example.com/image.jpg atau https://www.youtube.com/watch?v=dQw4w9WgXcQ</li>
             </ul>
         </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-md flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-600 text-white rounded-md">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Media Settings Form -->
    <form method="POST" action="{{ route('admin.media.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Home Hero Image -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Home Hero Image</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Left Column: Upload & Preview -->
                <div class="space-y-4">
                    <!-- Current Image Display -->
                    @if($mediaSettings['home_hero_image'] || $mediaSettings['home_hero_image_url'])
                    <div class="p-4 bg-white/10 rounded-lg border border-white/20" id="home-hero-current">
                        <p class="text-white/60 text-sm mb-3">Image saat ini:</p>
                        <div class="flex items-center justify-center">
                            <div class="relative">
                                @if($mediaSettings['home_hero_image_type'] === 'file' && $mediaSettings['home_hero_image'])
                                    <img src="{{ asset($mediaSettings['home_hero_image']) }}" alt="Home Hero" class="h-32 w-32 object-cover rounded-lg border border-white/20 shadow-sm">
                                @elseif($mediaSettings['home_hero_image_type'] === 'url' && $mediaSettings['home_hero_image_url'])
                                    <img src="{{ $mediaSettings['home_hero_image_url'] }}" alt="Home Hero" class="h-32 w-32 object-cover rounded-lg border border-white/20 shadow-sm">
                                @endif
                                <div class="absolute -top-2 -right-2">
                                    <button type="button" onclick="removeMedia('home_hero_image')" class="bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 justify-center mt-3">
                            <button type="button" onclick="showUploadArea('home-hero')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                Upload Ulang
                            </button>
                            <button type="button" onclick="removeMedia('home_hero_image')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Upload Type Selection -->
                    <div class="space-y-3">
                        <label class="block text-white/70 text-sm">Pilih Sumber:</label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="home_hero_image_type" value="file" 
                                       {{ $mediaSettings['home_hero_image_type'] === 'file' ? 'checked' : '' }}
                                       class="home-hero-type-radio">
                                <span class="text-white/80 text-sm">Upload File</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="home_hero_image_type" value="url" 
                                       {{ $mediaSettings['home_hero_image_type'] === 'url' ? 'checked' : '' }}
                                       class="home-hero-type-radio">
                                <span class="text-white/80 text-sm">URL Link</span>
                            </label>
                        </div>
                    </div>

                    <!-- File Upload Area -->
                    <div id="home-hero-file-upload" class="border-2 border-dashed border-white/20 rounded-lg p-4 text-center hover:border-emerald-400/50 transition-colors">
                        <div class="space-y-3" id="home-hero-upload-placeholder">
                            <svg class="mx-auto h-8 w-8 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="text-white/60">
                                <label for="home_hero_image" class="cursor-pointer text-emerald-400 hover:text-emerald-300 font-medium text-sm">
                                    Upload Image
                                </label>
                                <input id="home_hero_image" name="home_hero_image" type="file" class="hidden" accept="image/*">
                                <span class="text-white/40 text-sm"> atau drag and drop</span>
                            </div>
                            <p class="text-xs text-white/40">PNG, JPG, GIF, WebP hingga 50MB</p>
                        </div>
                        
                        <!-- Preview Area -->
                        <div id="home-hero-image-preview" class="hidden">
                            <div class="space-y-3">
                                <div class="relative">
                                    <img id="home-hero-preview-img" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-white/20">
                                    <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                        Preview
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-white/60 text-sm mb-3">Gambar akan ditampilkan di halaman utama</div>
                                    <div class="flex gap-2 justify-center">
                                        <button type="button" onclick="removePreview('home-hero')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                            Hapus
                                        </button>
                                        <button type="button" onclick="confirmUpload('home-hero')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                            Gunakan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- URL Input -->
                    <div id="home-hero-url-input" class="space-y-2">
                        <label class="block text-white/70 text-sm">URL Image:</label>
                        <div class="flex gap-2">
                            <input type="url" name="home_hero_image_url" id="home-hero_image_url"
                                   value="{{ $mediaSettings['home_hero_image_url'] }}"
                                   class="flex-1 px-3 py-2 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm"
                                   placeholder="https://example.com/image.jpg">
                             <!-- Preview button removed - using auto-preview -->
                        </div>
                        <p class="text-white/40 text-xs">Masukkan URL gambar yang akan ditampilkan di halaman utama.</p>
                        
                        <!-- URL Preview Area -->
                        <div id="home-hero-url-preview" style="display: none;" class="mt-4">
                            <div class="space-y-3">
                                <div class="relative">
                                    <img id="home-hero-url-preview-img" src="" alt="URL Preview" class="w-full h-48 object-cover rounded-lg border border-white/20">
                                    <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                        URL Preview
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-white/60 text-sm mb-3">Gambar dari URL akan ditampilkan di halaman utama</div>
                                    <div class="flex gap-2 justify-center">
                                        <button type="button" onclick="removeUrlPreview('home-hero')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                            Hapus Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info -->
                <div class="space-y-4">
                    <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                        <h4 class="font-medium text-white/90 mb-2">Informasi</h4>
                        <ul class="text-white/60 text-sm space-y-1">
                            <li>• Gambar akan ditampilkan di halaman utama</li>
                            <li>• Ukuran rekomendasi: 1200x600px</li>
                            <li>• Format: PNG, JPG, GIF, WebP</li>
                            <li>• Maksimal: 50MB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cara Beli Video -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Video Cara Beli</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Left Column: Upload & Preview -->
                <div class="space-y-4">
                    <!-- Current Video Display -->
                    @if($mediaSettings['cara_beli_video'] || $mediaSettings['cara_beli_video_url'])
                    <div class="p-4 bg-white/10 rounded-lg border border-white/20" id="cara-beli-current">
                        <p class="text-white/60 text-sm mb-3">Video saat ini:</p>
                        <div class="flex items-center justify-center">
                            <div class="relative">
                                @if($mediaSettings['cara_beli_video_type'] === 'file' && $mediaSettings['cara_beli_video'])
                                    <video class="h-32 w-32 object-cover rounded-lg border border-white/20 shadow-sm" controls>
                                        <source src="{{ asset($mediaSettings['cara_beli_video']) }}" type="video/mp4">
                                    </video>
                                @elseif($mediaSettings['cara_beli_video_type'] === 'url' && $mediaSettings['cara_beli_video_url'])
                                    @if(str_contains($mediaSettings['cara_beli_video_url'], 'youtube.com') || str_contains($mediaSettings['cara_beli_video_url'], 'youtu.be'))
                                        @php
                                            $videoId = '';
                                            if (str_contains($mediaSettings['cara_beli_video_url'], 'youtube.com/watch?v=')) {
                                                $videoId = explode('v=', $mediaSettings['cara_beli_video_url'])[1];
                                                $videoId = explode('&', $videoId)[0];
                                            } elseif (str_contains($mediaSettings['cara_beli_video_url'], 'youtu.be/')) {
                                                $videoId = explode('youtu.be/', $mediaSettings['cara_beli_video_url'])[1];
                                                $videoId = explode('?', $videoId)[0];
                                            }
                                        @endphp
                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="h-32 w-32 rounded-lg border border-white/20 shadow-sm" frameborder="0" allowfullscreen></iframe>
                                    @else
                                        <video class="h-32 w-32 object-cover rounded-lg border border-white/20 shadow-sm" controls>
                                            <source src="{{ $mediaSettings['cara_beli_video_url'] }}" type="video/mp4">
                                        </video>
                                    @endif
                                @endif
                                <div class="absolute -top-2 -right-2">
                                    <button type="button" onclick="removeMedia('cara_beli_video')" class="bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 justify-center mt-3">
                            <button type="button" onclick="showUploadArea('cara-beli')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                Upload Ulang
                            </button>
                            <button type="button" onclick="removeMedia('cara_beli_video')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Upload Type Selection -->
                    <div class="space-y-3">
                        <label class="block text-white/70 text-sm">Pilih Sumber:</label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="cara_beli_video_type" value="file" 
                                       {{ $mediaSettings['cara_beli_video_type'] === 'file' ? 'checked' : '' }}
                                       class="cara-beli-type-radio">
                                <span class="text-white/80 text-sm">Upload File</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="cara_beli_video_type" value="url" 
                                       {{ $mediaSettings['cara_beli_video_type'] === 'url' ? 'checked' : '' }}
                                       class="cara-beli-type-radio">
                                <span class="text-white/80 text-sm">URL Link</span>
                            </label>
                        </div>
                    </div>

                    <!-- File Upload Area -->
                    <div id="cara-beli-file-upload" class="border-2 border-dashed border-white/20 rounded-lg p-4 text-center hover:border-emerald-400/50 transition-colors">
                        <div class="space-y-3" id="cara-beli-upload-placeholder">
                            <svg class="mx-auto h-8 w-8 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <div class="text-white/60">
                                <label for="cara_beli_video" class="cursor-pointer text-emerald-400 hover:text-emerald-300 font-medium text-sm">
                                    Upload Video
                                </label>
                                <input id="cara_beli_video" name="cara_beli_video" type="file" class="hidden" accept="video/*">
                                <span class="text-white/40 text-sm"> atau drag and drop</span>
                            </div>
                            <p class="text-xs text-white/40">MP4, AVI, MOV, WMV, FLV, WebM hingga 50MB</p>
                        </div>
                        
                        <!-- Preview Area -->
                        <div id="cara-beli-video-preview" class="hidden">
                            <div class="space-y-3">
                                <div class="relative">
                                    <video id="cara-beli-preview-video" src="" controls class="w-full h-48 object-cover rounded-lg border border-white/20">
                                        Browser Anda tidak mendukung video.
                                    </video>
                                    <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                        Preview
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-white/60 text-sm mb-3">Video akan ditampilkan di modal "Cara Beli"</div>
                                    <div class="flex gap-2 justify-center">
                                        <button type="button" onclick="removePreview('cara-beli')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                            Hapus
                                        </button>
                                        <button type="button" onclick="confirmUpload('cara-beli')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                            Gunakan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- URL Input -->
                    <div id="cara-beli-url-input" class="space-y-2">
                        <label class="block text-white/70 text-sm">URL Video:</label>
                        <div class="flex gap-2">
                            <input type="url" name="cara_beli_video_url" id="cara-beli_video_url"
                                   value="{{ $mediaSettings['cara_beli_video_url'] }}"
                                   class="flex-1 px-3 py-2 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm"
                                   placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                             <!-- Preview button removed - using auto-preview -->
                        </div>
                        <p class="text-white/40 text-xs">Masukkan URL YouTube yang akan ditampilkan di modal "Cara Beli".</p>
                        
                         <!-- URL Preview Area -->
                         <div id="cara-beli-url-preview" style="display: none;" class="mt-4">
                             <div class="space-y-3">
                                 <div class="relative">
                                     <div id="cara-beli-url-preview-video" class="w-full h-48 rounded-lg border border-white/20 overflow-hidden">
                                         <!-- YouTube iframe will be inserted here -->
                                     </div>
                                     <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                         URL Preview
                                     </div>
                                 </div>
                                 <div class="text-center">
                                     <div class="text-white/60 text-sm mb-3">Video YouTube akan ditampilkan di modal "Cara Beli"</div>
                                     <div class="flex gap-2 justify-center">
                                         <button type="button" onclick="removeUrlPreview('cara-beli')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                             Hapus Preview
                                         </button>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- Right Column: Info -->
                <div class="space-y-4">
                    <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                        <h4 class="font-medium text-white/90 mb-2">Informasi</h4>
                        <ul class="text-white/60 text-sm space-y-1">
                            <li>• Video YouTube akan ditampilkan di halaman search</li>
                            <li>• Link "Cara Beli" akan membuka video ini</li>
                            <li>• Format: YouTube URL (watch/embed/short)</li>
                            <li>• Maksimal: 50MB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cara Bikin Gamepass Video -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Video Cara Bikin Gamepass</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Left Column: Upload & Preview -->
                <div class="space-y-4">
                    <!-- Current Video Display -->
                    @if($mediaSettings['cara_bikin_gamepass_video'] || $mediaSettings['cara_bikin_gamepass_video_url'])
                    <div class="p-4 bg-white/10 rounded-lg border border-white/20" id="cara-gamepass-current">
                        <p class="text-white/60 text-sm mb-3">Video saat ini:</p>
                        <div class="flex items-center justify-center">
                            <div class="relative">
                                @if($mediaSettings['cara_bikin_gamepass_video_type'] === 'file' && $mediaSettings['cara_bikin_gamepass_video'])
                                    <video class="h-32 w-32 object-cover rounded-lg border border-white/20 shadow-sm" controls>
                                        <source src="{{ asset($mediaSettings['cara_bikin_gamepass_video']) }}" type="video/mp4">
                                    </video>
                                @elseif($mediaSettings['cara_bikin_gamepass_video_type'] === 'url' && $mediaSettings['cara_bikin_gamepass_video_url'])
                                    @if(str_contains($mediaSettings['cara_bikin_gamepass_video_url'], 'youtube.com') || str_contains($mediaSettings['cara_bikin_gamepass_video_url'], 'youtu.be'))
                                        @php
                                            $videoId = '';
                                            if (str_contains($mediaSettings['cara_bikin_gamepass_video_url'], 'youtube.com/watch?v=')) {
                                                $videoId = explode('v=', $mediaSettings['cara_bikin_gamepass_video_url'])[1];
                                                $videoId = explode('&', $videoId)[0];
                                            } elseif (str_contains($mediaSettings['cara_bikin_gamepass_video_url'], 'youtu.be/')) {
                                                $videoId = explode('youtu.be/', $mediaSettings['cara_bikin_gamepass_video_url'])[1];
                                                $videoId = explode('?', $videoId)[0];
                                            }
                                        @endphp
                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="h-32 w-32 rounded-lg border border-white/20 shadow-sm" frameborder="0" allowfullscreen></iframe>
                                    @else
                                        <video class="h-32 w-32 object-cover rounded-lg border border-white/20 shadow-sm" controls>
                                            <source src="{{ $mediaSettings['cara_bikin_gamepass_video_url'] }}" type="video/mp4">
                                        </video>
                                    @endif
                                @endif
                                <div class="absolute -top-2 -right-2">
                                    <button type="button" onclick="removeMedia('cara_bikin_gamepass_video')" class="bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 justify-center mt-3">
                            <button type="button" onclick="showUploadArea('cara-gamepass')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                Upload Ulang
                            </button>
                            <button type="button" onclick="removeMedia('cara_bikin_gamepass_video')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Upload Type Selection -->
                    <div class="space-y-3">
                        <label class="block text-white/70 text-sm">Pilih Sumber:</label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="cara_bikin_gamepass_video_type" value="file" 
                                       {{ $mediaSettings['cara_bikin_gamepass_video_type'] === 'file' ? 'checked' : '' }}
                                       class="cara-gamepass-type-radio">
                                <span class="text-white/80 text-sm">Upload File</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="cara_bikin_gamepass_video_type" value="url" 
                                       {{ $mediaSettings['cara_bikin_gamepass_video_type'] === 'url' ? 'checked' : '' }}
                                       class="cara-gamepass-type-radio">
                                <span class="text-white/80 text-sm">URL Link</span>
                            </label>
                        </div>
                    </div>

                    <!-- File Upload Area -->
                    <div id="cara-gamepass-file-upload" class="border-2 border-dashed border-white/20 rounded-lg p-4 text-center hover:border-emerald-400/50 transition-colors">
                        <div class="space-y-3" id="cara-gamepass-upload-placeholder">
                            <svg class="mx-auto h-8 w-8 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <div class="text-white/60">
                                <label for="cara_bikin_gamepass_video" class="cursor-pointer text-emerald-400 hover:text-emerald-300 font-medium text-sm">
                                    Upload Video
                                </label>
                                <input id="cara_bikin_gamepass_video" name="cara_bikin_gamepass_video" type="file" class="hidden" accept="video/*">
                                <span class="text-white/40 text-sm"> atau drag and drop</span>
                            </div>
                            <p class="text-xs text-white/40">MP4, AVI, MOV, WMV, FLV, WebM hingga 50MB</p>
                        </div>
                        
                        <!-- Preview Area -->
                        <div id="cara-gamepass-video-preview" class="hidden">
                            <div class="space-y-3">
                                <div class="relative">
                                    <video id="cara-gamepass-preview-video" src="" controls class="w-full h-48 object-cover rounded-lg border border-white/20">
                                        Browser Anda tidak mendukung video.
                                    </video>
                                    <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                        Preview
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="text-white/60 text-sm mb-3">Video akan ditampilkan di modal "Cara Bikin Gamepass"</div>
                                    <div class="flex gap-2 justify-center">
                                        <button type="button" onclick="removePreview('cara-gamepass')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                            Hapus
                                        </button>
                                        <button type="button" onclick="confirmUpload('cara-gamepass')" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                            Gunakan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- URL Input -->
                    <div id="cara-gamepass-url-input" class="space-y-2">
                        <label class="block text-white/70 text-sm">URL Video:</label>
                        <div class="flex gap-2">
                            <input type="url" name="cara_bikin_gamepass_video_url" id="cara-bikin_gamepass_video_url"
                                   value="{{ $mediaSettings['cara_bikin_gamepass_video_url'] }}"
                                   class="flex-1 px-3 py-2 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm"
                                   placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                             <!-- Preview button removed - using auto-preview -->
                        </div>
                        <p class="text-white/40 text-xs">Masukkan URL YouTube yang akan ditampilkan di modal "Cara Bikin Gamepass".</p>
                        
                         <!-- URL Preview Area -->
                         <div id="cara-gamepass-url-preview" style="display: none;" class="mt-4">
                             <div class="space-y-3">
                                 <div class="relative">
                                     <div id="cara-gamepass-url-preview-video" class="w-full h-48 rounded-lg border border-white/20 overflow-hidden">
                                         <!-- YouTube iframe will be inserted here -->
                                     </div>
                                     <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                         URL Preview
                                     </div>
                                 </div>
                                 <div class="text-center">
                                     <div class="text-white/60 text-sm mb-3">Video YouTube akan ditampilkan di modal "Cara Bikin Gamepass"</div>
                                     <div class="flex gap-2 justify-center">
                                         <button type="button" onclick="removeUrlPreview('cara-gamepass')" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                             Hapus Preview
                                         </button>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>

                <!-- Right Column: Info -->
                <div class="space-y-4">
                    <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                        <h4 class="font-medium text-white/90 mb-2">Informasi</h4>
                        <ul class="text-white/60 text-sm space-y-1">
                            <li>• Video YouTube akan ditampilkan di modal gamepass</li>
                            <li>• Link "Cara Bikin Gamepass" akan membuka video ini</li>
                            <li>• Format: YouTube URL (watch/embed/short)</li>
                            <li>• Maksimal: 50MB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button type="submit" id="submitBtn" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition text-sm sm:text-base flex items-center gap-2">
                <span id="submitText">Update Media Settings</span>
                <svg id="submitSpinner" class="w-4 h-4 animate-spin hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ route('admin.media') }}" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition text-sm sm:text-base text-center">
                Reset
            </a>
        </div>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize type radio buttons
    initializeTypeRadios('home-hero');
    initializeTypeRadios('cara-beli');
    initializeTypeRadios('cara-gamepass');

    // Initialize file uploads
    initializeFileUpload('home-hero', 'image');
    initializeFileUpload('cara-beli', 'video');
    initializeFileUpload('cara-gamepass', 'video');

    // Add form submission loading state
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitText.textContent = 'Uploading...';
            submitSpinner.classList.remove('hidden');
        });
    }
});

function initializeTypeRadios(type) {
    const radios = document.querySelectorAll(`.${type}-type-radio`);
    const fileUpload = document.getElementById(`${type}-file-upload`);
    const urlInput = document.getElementById(`${type}-url-input`);

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'file') {
                fileUpload.style.display = 'block';
                urlInput.style.display = 'none';
            } else {
                fileUpload.style.display = 'none';
                urlInput.style.display = 'block';
            }
        });
    });

    // Set initial state
    const checkedRadio = document.querySelector(`.${type}-type-radio:checked`);
    if (checkedRadio) {
        checkedRadio.dispatchEvent(new Event('change'));
    }
}

function initializeFileUpload(type, mediaType) {
    let fileInput;
    if (type === 'cara-gamepass') {
        fileInput = document.getElementById('cara_bikin_gamepass_video');
    } else if (type === 'cara-beli') {
        fileInput = document.getElementById('cara_beli_video');
    } else if (type === 'home-hero') {
        fileInput = document.getElementById('home_hero_image');
    } else {
        fileInput = document.getElementById(`${type}_${mediaType === 'image' ? 'image' : 'video'}`);
    }
    const uploadPlaceholder = document.getElementById(`${type}-upload-placeholder`);
    const preview = document.getElementById(`${type}-${mediaType}-preview`);
    let previewImg;
    if (type === 'home-hero') {
        previewImg = document.getElementById('home-hero-preview-img');
    } else {
        previewImg = document.getElementById(`${type}-preview-${mediaType}`);
    }

     if (fileInput) {
         fileInput.addEventListener('change', handleFileSelect);
         
         // Drag and drop functionality
         const uploadArea = document.getElementById(`${type}-file-upload`);
         if (uploadArea) {
             uploadArea.addEventListener('dragover', handleDragOver);
             uploadArea.addEventListener('drop', handleDrop);
             uploadArea.addEventListener('dragleave', handleDragLeave);
         }
     }

    function handleDragOver(event) {
        event.preventDefault();
        event.currentTarget.classList.add('border-emerald-400', 'bg-emerald-500/10');
    }

    function handleDragLeave(event) {
        event.preventDefault();
        event.currentTarget.classList.remove('border-emerald-400', 'bg-emerald-500/10');
    }

    function handleDrop(event) {
        event.preventDefault();
        event.currentTarget.classList.remove('border-emerald-400', 'bg-emerald-500/10');
        
        const files = event.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if ((mediaType === 'image' && file.type.startsWith('image/')) || 
                (mediaType === 'video' && file.type.startsWith('video/'))) {
                showPreview(file);
                fileInput.files = files;
            }
        }
    }

     function showPreview(file) {
         const reader = new FileReader();
         reader.onload = function(e) {
             if (mediaType === 'image') {
                 if (previewImg) {
                     previewImg.src = e.target.result;
                 }
             } else if (mediaType === 'video') {
                 let previewVideo;
                 if (type === 'cara-beli') {
                     previewVideo = document.getElementById('cara-beli-preview-video');
                 } else if (type === 'cara-gamepass') {
                     previewVideo = document.getElementById('cara-gamepass-preview-video');
                 } else {
                     previewVideo = document.getElementById(`${type}-preview-${mediaType}`);
                 }
                 if (previewVideo) {
                     previewVideo.src = e.target.result;
                 }
             }
             uploadPlaceholder.classList.add('hidden');
             preview.classList.remove('hidden');
         };
         reader.readAsDataURL(file);
     }

     // Auto-preview for file uploads
     function handleFileSelect(event) {
         const file = event.target.files[0];
         if (file) {
             showPreview(file);
         }
     }

     function handleDrop(event) {
         event.preventDefault();
         event.currentTarget.classList.remove('border-emerald-400', 'bg-emerald-500/10');
         
         const files = event.dataTransfer.files;
         if (files.length > 0) {
             const file = files[0];
             if ((mediaType === 'image' && file.type.startsWith('image/')) || 
                 (mediaType === 'video' && file.type.startsWith('video/'))) {
                 showPreview(file);
                 fileInput.files = files;
             }
         }
     }

    // Preview URL function
    function previewUrl(url) {
        if (mediaType === 'image') {
            previewImg.src = url;
        } else if (mediaType === 'video') {
            const previewVideo = document.getElementById(`${type}-preview-${mediaType}`);
            if (previewVideo) {
                previewVideo.src = url;
            }
        }
        uploadPlaceholder.classList.add('hidden');
        preview.classList.remove('hidden');
    }

     // Add URL preview functionality with auto-preview
     let urlInput;
     if (type === 'cara-gamepass') {
         urlInput = document.getElementById('cara-bikin_gamepass_video_url');
     } else if (type === 'cara-beli') {
         urlInput = document.getElementById('cara-beli_video_url');
     } else if (type === 'home-hero') {
         urlInput = document.getElementById('home-hero_image_url');
     } else {
         urlInput = document.getElementById(`${type}_${mediaType === 'image' ? 'image' : 'video'}_url`);
     }
     const previewUrlBtn = document.getElementById(`${type}-preview-url-btn`);
     
     if (urlInput) {
         // Auto-preview on input with debounce
         let timeoutId;
         urlInput.addEventListener('input', function() {
             const url = this.value.trim();
             
             // Clear previous timeout
             clearTimeout(timeoutId);
             
             // Hide preview button since we're using auto-preview
             if (previewUrlBtn) {
                 previewUrlBtn.style.display = 'none';
             }
             
             // Auto-preview after 1 second delay
             timeoutId = setTimeout(() => {
                 if (url && (isValidUrl(url) || isValidYouTubeUrl(url))) {
                     window.previewUrl(type, url);
                 } else {
                     // Hide preview if URL is invalid
                     window.hideUrlPreview(type);
                 }
             }, 1000);
         });
     }

    // Global functions for buttons
    window[`removePreview_${type}`] = function() {
        uploadPlaceholder.classList.remove('hidden');
        preview.classList.add('hidden');
        fileInput.value = '';
        if (mediaType === 'image') {
            if (previewImg) {
                previewImg.src = '';
            }
        } else if (mediaType === 'video') {
            let previewVideo;
            if (type === 'cara-beli') {
                previewVideo = document.getElementById('cara-beli-preview-video');
            } else if (type === 'cara-gamepass') {
                previewVideo = document.getElementById('cara-gamepass-preview-video');
            } else {
                previewVideo = document.getElementById(`${type}-preview-${mediaType}`);
            }
            if (previewVideo) {
                previewVideo.src = '';
            }
        }
    };

    window[`confirmUpload_${type}`] = function() {
        // Image is already selected, just keep the preview visible
        // The form will submit with the selected file
    };
}

// Global functions
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

function isValidYouTubeUrl(string) {
    const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/)|youtu\.be\/)[\w-]+/;
    return youtubeRegex.test(string);
}

function convertToYouTubeEmbed(url) {
    // Convert YouTube watch URL to embed URL
    if (url.includes('youtube.com/watch?v=')) {
        const videoId = url.split('v=')[1].split('&')[0];
        return `https://www.youtube.com/embed/${videoId}`;
    }
    // Convert YouTube short URL to embed URL
    if (url.includes('youtu.be/')) {
        const videoId = url.split('youtu.be/')[1].split('?')[0];
        return `https://www.youtube.com/embed/${videoId}`;
    }
    // Already embed URL
    if (url.includes('youtube.com/embed/')) {
        return url;
    }
    return url;
}

window.showUploadArea = function(type) {
    const uploadPlaceholder = document.getElementById(`${type}-upload-placeholder`);
    const preview = document.getElementById(`${type}-${type.includes('hero') ? 'image' : 'video'}-preview`);
    if (uploadPlaceholder && preview) {
        uploadPlaceholder.classList.remove('hidden');
        preview.classList.add('hidden');
    }
    
    // Reset file input
    const fileInput = document.getElementById(`${type}_${type.includes('hero') ? 'image' : 'video'}`);
    if (fileInput) {
        fileInput.value = '';
    }
};

window.removePreview = function(type) {
    const uploadPlaceholder = document.getElementById(`${type}-upload-placeholder`);
    const preview = document.getElementById(`${type}-${type.includes('hero') ? 'image' : 'video'}-preview`);
    if (uploadPlaceholder && preview) {
        uploadPlaceholder.classList.remove('hidden');
        preview.classList.add('hidden');
    }
    
    // Reset file input
    let fileInput;
    if (type === 'home-hero') {
        fileInput = document.getElementById('home_hero_image');
    } else if (type === 'cara-beli') {
        fileInput = document.getElementById('cara_beli_video');
    } else if (type === 'cara-gamepass') {
        fileInput = document.getElementById('cara_bikin_gamepass_video');
    } else {
        fileInput = document.getElementById(`${type}_${type.includes('hero') ? 'image' : 'video'}`);
    }
    if (fileInput) {
        fileInput.value = '';
    }
    
    // Clear preview content
    if (type.includes('hero')) {
        const previewImg = document.getElementById('home-hero-preview-img');
        if (previewImg) {
            previewImg.src = '';
        }
    } else {
        let previewVideo;
        if (type === 'cara-beli') {
            previewVideo = document.getElementById('cara-beli-preview-video');
        } else if (type === 'cara-gamepass') {
            previewVideo = document.getElementById('cara-gamepass-preview-video');
        } else {
            previewVideo = document.getElementById(`${type}-preview-video`);
        }
        if (previewVideo) {
            previewVideo.src = '';
        }
    }
};

window.confirmUpload = function(type) {
    // Image/Video is already selected, just keep the preview visible
    // The form will submit with the selected file
};

// URL Preview functions
window.previewUrl = function(type, url) {
    // Find preview elements with more flexible selectors
    let previewElement, urlPreview;
    
    if (type === 'home-hero') {
        previewElement = document.getElementById('home-hero-url-preview-img');
        urlPreview = document.getElementById('home-hero-url-preview');
    } else if (type === 'cara-beli') {
        previewElement = document.getElementById('cara-beli-url-preview-video');
        urlPreview = document.getElementById('cara-beli-url-preview');
    } else if (type === 'cara-gamepass') {
        previewElement = document.getElementById('cara-gamepass-url-preview-video');
        urlPreview = document.getElementById('cara-gamepass-url-preview');
    }
    
    if (previewElement && urlPreview) {
        const mediaType = type.includes('hero') ? 'image' : 'video';
        
        if (mediaType === 'video' && isValidYouTubeUrl(url)) {
            // For YouTube videos, create iframe instead of video element
            const embedUrl = convertToYouTubeEmbed(url);
            previewElement.innerHTML = `<iframe src="${embedUrl}" frameborder="0" allowfullscreen class="w-full h-48 rounded-lg"></iframe>`;
        } else {
            previewElement.src = url;
        }
        urlPreview.style.display = 'block';
    }
};

window.hideUrlPreview = function(type) {
    // Find preview elements with more flexible selectors
    let previewElement, urlPreview;
    
    if (type === 'home-hero') {
        previewElement = document.getElementById('home-hero-url-preview-img');
        urlPreview = document.getElementById('home-hero-url-preview');
    } else if (type === 'cara-beli') {
        previewElement = document.getElementById('cara-beli-url-preview-video');
        urlPreview = document.getElementById('cara-beli-url-preview');
    } else if (type === 'cara-gamepass') {
        previewElement = document.getElementById('cara-gamepass-url-preview-video');
        urlPreview = document.getElementById('cara-gamepass-url-preview');
    }
    
    if (previewElement && urlPreview) {
        const mediaType = type.includes('hero') ? 'image' : 'video';
        
        if (mediaType === 'video') {
            // Reset to empty div for YouTube
            previewElement.innerHTML = '<!-- YouTube iframe will be inserted here -->';
        } else {
            previewElement.src = '';
        }
        urlPreview.style.display = 'none';
    }
};

window.removeUrlPreview = function(type) {
    window.hideUrlPreview(type);
};


window.removeMedia = function(type) {
    if (confirm('Apakah Anda yakin ingin menghapus media ini?')) {
        fetch('/admin/media/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghapus media');
        });
    }
};
</script>
@endsection
