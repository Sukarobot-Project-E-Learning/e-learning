@extends('client.main')
@section('body')

<!-- Article Detail -->
<main class="max-w-5xl mx-auto px-6 pt-28 pb-12">

  <!-- Breadcrumb -->
  <nav class="mb-6 text-sm">
    <ol class="flex items-center space-x-2 text-gray-600">
      <li><a href="{{ route('client.home') }}" class="hover:text-orange-600 transition-colors">Beranda</a></li>
      <li><span class="text-gray-400">/</span></li>
      <li><a href="{{ route('client.artikel') }}" class="hover:text-orange-600 transition-colors">Artikel</a></li>
      <li><span class="text-gray-400">/</span></li>
      <li class="text-orange-600 font-medium">{{ Str::limit($article->title, 50) }}</li>
    </ol>
  </nav>

  <!-- Article Header -->
  <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <!-- Featured Image -->
    <div class="w-full h-96 bg-gradient-to-br from-orange-100 to-blue-100">
      <img src="{{ $article->image_url }}" 
           alt="{{ $article->title }}" 
           class="w-full h-full object-cover"
           onerror="this.src='{{ asset('assets/elearning/client/img/default-article.jpg') }}'">
    </div>

    <!-- Article Content -->
    <div class="p-8 md:p-12">
      <!-- Meta Info -->
      <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-600">
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-600 font-semibold">
          {{ $article->category }}
        </span>
        <span class="flex items-center">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          {{ $article->formatted_published_date }}
        </span>
        <span class="flex items-center">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
          {{ number_format($article->views) }} views
        </span>
        @if($article->author)
        <span class="flex items-center">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          {{ $article->author->name }}
        </span>
        @endif
      </div>

      <!-- Title -->
      <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
        {{ $article->title }}
      </h1>

      <!-- Excerpt -->
      @if($article->excerpt)
      <div class="mb-8 p-6 bg-gray-50 border-l-4 border-orange-500 rounded-r-lg">
        <p class="text-lg text-gray-700 italic">
          {{ $article->excerpt }}
        </p>
      </div>
      @endif

      <!-- Content -->
      <div class="prose prose-lg max-w-none">
        <div class="article-content text-gray-800 leading-relaxed">
          {!! $article->content !!}
        </div>
      </div>

      <!-- Share Buttons -->
      <div class="mt-12 pt-8 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bagikan Artikel:</h3>
        <div class="flex flex-wrap gap-3">
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
             target="_blank"
             class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Facebook
          </a>
          <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" 
             target="_blank"
             class="inline-flex items-center px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
            </svg>
            Twitter
          </a>
          <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . request()->url()) }}" 
             target="_blank"
             class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
            WhatsApp
          </a>
        </div>
      </div>
    </div>
  </article>

  <!-- Related Articles -->
  @if($relatedArticles->count() > 0)
  <section class="mt-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Artikel Terkait</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($relatedArticles as $related)
      <a href="{{ route('client.artikel.detail', $related->slug) }}" 
         class="block bg-white rounded-xl overflow-hidden hover:shadow-xl transition-all duration-200 hover:scale-[1.02]">
        <img src="{{ $related->image_url }}" 
             alt="{{ $related->title }}" 
             class="w-full h-40 object-cover"
             onerror="this.src='{{ asset('assets/elearning/client/img/default-article.jpg') }}'">
        <div class="p-4">
          <span class="text-xs font-semibold text-orange-600">{{ $related->category }}</span>
          <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $related->title }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ Str::limit($related->excerpt, 80) }}</p>
          <p class="text-xs text-gray-400 mt-2">{{ $related->formatted_published_date }}</p>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

  <!-- Back to Articles -->
  <div class="mt-12 text-center">
    <a href="{{ route('client.artikel') }}" 
       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-blue-900 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
      </svg>
      Kembali ke Daftar Artikel
    </a>
  </div>

</main>

<!-- Custom Styles for Article Content -->
<style>
.article-content h1 {
  font-size: 2rem;
  font-weight: 700;
  margin-top: 2rem;
  margin-bottom: 1rem;
  color: #111827;
}

.article-content h2 {
  font-size: 1.75rem;
  font-weight: 700;
  margin-top: 1.75rem;
  margin-bottom: 0.875rem;
  color: #111827;
}

.article-content h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  color: #374151;
}

.article-content p {
  margin-bottom: 1.25rem;
  line-height: 1.8;
}

.article-content ul, .article-content ol {
  margin-bottom: 1.25rem;
  margin-left: 1.5rem;
}

.article-content li {
  margin-bottom: 0.5rem;
}

.article-content img {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin: 1.5rem auto;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.article-content blockquote {
  border-left: 4px solid #f97316;
  padding-left: 1rem;
  margin: 1.5rem 0;
  font-style: italic;
  color: #4b5563;
}

.article-content a {
  color: #f97316;
  text-decoration: underline;
}

.article-content a:hover {
  color: #ea580c;
}

.article-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 1.5rem 0;
}

.article-content table th,
.article-content table td {
  border: 1px solid #e5e7eb;
  padding: 0.75rem;
  text-align: left;
}

.article-content table th {
  background-color: #f3f4f6;
  font-weight: 600;
}

.article-content code {
  background-color: #f3f4f6;
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  font-family: 'Courier New', monospace;
  font-size: 0.875em;
}

.article-content pre {
  background-color: #1f2937;
  color: #f9fafb;
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin: 1.5rem 0;
}

.article-content pre code {
  background-color: transparent;
  padding: 0;
  color: inherit;
}
</style>

@include('client.partials.footer')
@endsection

