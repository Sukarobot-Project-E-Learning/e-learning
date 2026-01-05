@extends('client.main')
@section('body')

@push('meta')
    <meta name="description" content="{{ $article->meta_description ?? $article->excerpt ?? Str::limit(strip_tags($article->content), 160) }}">
    <meta name="keywords" content="{{ $article->meta_keywords ?? $article->category }}">
    @if($article->meta_title)
        <title>{{ $article->meta_title }} - Sukarobot</title>
    @endif
@endpush

<!-- Article Detail -->
<main class="max-w-5xl mx-auto px-6 pt-32 pb-20">

  <!-- Breadcrumb -->
  <nav class="mb-8 text-sm font-medium">
    <ol class="flex items-center space-x-3 text-gray-500">
      <li><a href="{{ route('client.home') }}" class="hover:text-blue-600 transition-colors">Beranda</a></li>
      <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
      <li><a href="{{ route('client.artikel') }}" class="hover:text-blue-600 transition-colors">Artikel</a></li>
      <li><svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
      <li class="text-blue-600 truncate max-w-[200px] md:max-w-none">{{ Str::limit($article->title, 50) }}</li>
    </ol>
  </nav>

  <!-- Article Header -->
  <article class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Featured Image -->
    <div class="w-full h-[400px] md:h-[500px] relative">
      <img src="{{ $article->image_url }}" 
           alt="{{ $article->title }}" 
           class="w-full h-full object-cover"
           onerror="this.src='{{ asset('assets/elearning/client/img/default-article.jpg') }}'">
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
      
      <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full">
          <span class="inline-block px-4 py-1.5 rounded-full bg-blue-600 text-white text-sm font-bold mb-4 shadow-lg">
            {{ $article->category }}
          </span>
          <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 leading-tight drop-shadow-md">
            {{ $article->title }}
          </h1>
          
          <div class="flex flex-wrap items-center gap-6 text-white/90 text-sm font-medium">
            <span class="flex items-center gap-2">
              <img src="https://ui-avatars.com/api/?name={{ urlencode($article->author->name ?? 'Admin') }}&background=random" class="w-8 h-8 rounded-full border-2 border-white/50">
              {{ $article->author->name ?? 'Admin' }}
            </span>
            <span class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              {{ $article->formatted_published_date }}
            </span>
            <span class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
              {{ number_format($article->views) }} views
            </span>
          </div>
      </div>
    </div>

    <!-- Article Content -->
    <div class="p-8 md:p-12">
      
      <!-- Content -->
      <div class="prose prose-lg max-w-none prose-blue prose-headings:font-bold prose-a:text-blue-600 hover:prose-a:text-blue-700 prose-img:rounded-2xl prose-img:shadow-lg">
        @if($article->excerpt)
            <div class="text-xl text-gray-500 font-medium italic mb-8 border-l-4 border-blue-500 pl-4 bg-blue-50 py-4 pr-4 rounded-r-lg">
                {{ $article->excerpt }}
            </div>
        @endif

        <div class="article-content text-gray-700 leading-relaxed">
          {!! $article->content !!}
        </div>

        @if($article->meta_keywords)
        <div class="mt-8 flex flex-wrap gap-2">
            @foreach(explode(',', $article->meta_keywords) as $keyword)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors cursor-default">
                    #{{ trim($keyword) }}
                </span>
            @endforeach
        </div>
        @endif
      </div>

      <!-- Share Buttons -->
      <div class="mt-16 pt-8 border-t border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
            Bagikan Artikel Ini
        </h3>
        <div class="flex flex-wrap gap-3">
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
             target="_blank"
             class="inline-flex items-center px-5 py-2.5 bg-[#1877F2] text-white rounded-xl font-medium hover:bg-blue-700 transition-all hover:-translate-y-0.5 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            Facebook
          </a>
          <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" 
             target="_blank"
             class="inline-flex items-center px-5 py-2.5 bg-[#1DA1F2] text-white rounded-xl font-medium hover:bg-sky-600 transition-all hover:-translate-y-0.5 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
            Twitter
          </a>
          <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . request()->url()) }}" 
             target="_blank"
             class="inline-flex items-center px-5 py-2.5 bg-[#25D366] text-white rounded-xl font-medium hover:bg-green-600 transition-all hover:-translate-y-0.5 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            WhatsApp
          </a>
        </div>
      </div>
    </div>
  </article>

  <!-- Related Articles -->
  @if($relatedArticles->count() > 0)
  <section class="mt-20">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Artikel Terkait</h2>
        <a href="{{ route('client.artikel') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @foreach($relatedArticles as $related)
      <a href="{{ route('client.artikel.detail', $related->slug) }}" 
         class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100">
        <div class="relative overflow-hidden h-48">
            <img src="{{ $related->image_url }}" 
                 alt="{{ $related->title }}" 
                 class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700"
                 onerror="this.src='{{ asset('assets/elearning/client/img/default-article.jpg') }}'">
            <div class="absolute top-3 left-3">
                <span class="px-2.5 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-blue-600 shadow-sm">{{ $related->category }}</span>
            </div>
        </div>
        <div class="p-5">
          <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $related->title }}</h3>
          <p class="text-xs text-gray-400 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              {{ $related->formatted_published_date }}
          </p>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

</main>

<!-- Custom Styles for Article Content -->
<style>
.article-content h1, .article-content h2, .article-content h3 {
  color: #111827;
  margin-top: 2em;
  margin-bottom: 0.5em;
  line-height: 1.3;
}
.article-content h1 { font-size: 2.25em; }
.article-content h2 { font-size: 1.75em; }
.article-content h3 { font-size: 1.5em; }
.article-content p { margin-bottom: 1.5em; line-height: 1.8; }
.article-content ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1.5em; }
.article-content ol { list-style-type: decimal; padding-left: 1.5em; margin-bottom: 1.5em; }
.article-content blockquote { border-left: 4px solid #3b82f6; padding-left: 1em; font-style: italic; color: #4b5563; margin: 1.5em 0; background: #eff6ff; padding: 1.5em; border-radius: 0 0.5rem 0.5rem 0; }
.article-content img { border-radius: 1rem; margin: 2em auto; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
</style>

@endsection
