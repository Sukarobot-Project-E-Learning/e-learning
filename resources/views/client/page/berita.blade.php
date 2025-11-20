@extends('client.main')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/home.css') }}">
@endsection

@section('body')

<!-- Konten Utama -->
<main class="max-w-4xl mx-auto px-6 pt-28 pb-16">
    <article id="article" class="bg-white rounded-xl shadow-md overflow-hidden">
      <img id="image" class="w-full h-64 object-cover" />
      <div class="p-6 sm:p-8">
        <span id="category" class="text-sm font-semibold text-blue-600"></span>
        <h1 id="title" class="text-3xl sm:text-4xl font-bold mt-2 text-gray-900"></h1>
        <p id="date" class="text-sm text-gray-500 mt-2"></p>
        <div id="content" class="mt-6 text-gray-700 leading-relaxed space-y-4 text-lg"></div>
      </div>
    </article>

    <!-- Artikel terkait -->
    <section class="mt-14">
      <h2 class="text-xl font-bold text-gray-900 mb-6">Baca juga</h2>
      <div id="related" class="grid gap-6 sm:grid-cols-2"></div>
    </section>
  </main>

<script>
    const params = new URLSearchParams(window.location.search);
    const id = parseInt(params.get("id"));

    // ✅ Semua gambar diganti jadi gambar lokal blog1.jpeg
    const articles = [
      {
        id: 1,
        title: "Robot Kolaboratif Membantu Pabrik Pintar",
        excerpt: "Implementasi cobot di lini produksi meningkatkan efisiensi 30%.",
        content: `
          <p>Implementasi cobot (collaborative robot) di lini produksi pabrik X meningkatkan efisiensi hingga 30%.</p>
          <p>Robot ini bekerja berdampingan dengan manusia untuk mempercepat proses produksi dan mengurangi kesalahan.</p>
        `,
        category: "Product",
        date: "2025-09-29",
        image: "/client/img/blog1.jpeg"
      },
      {
        id: 2,
        title: "Lengan Robotik Baru untuk Industri Otomotif",
        excerpt: "Produk terbaru menghadirkan presisi lebih tinggi dalam perakitan mobil modern.",
        content: `
          <p>Lengan robotik generasi terbaru membawa presisi tinggi dalam industri otomotif.</p>
          <p>Teknologi ini mendukung produksi mobil listrik dengan tingkat akurasi yang lebih baik.</p>
        `,
        category: "Product",
        date: "2025-09-20",
        image: "/client/img/blog1.jpeg"
      },
      {
        id: 3,
        title: "Robot Service di Rumah Sakit",
        excerpt: "Robot membantu distribusi obat di rumah sakit besar Jakarta.",
        content: `
          <p>Robot pelayanan rumah sakit mulai digunakan untuk mendistribusikan obat dan kebutuhan medis.</p>
          <p>Teknologi ini diharapkan mengurangi beban tenaga medis dalam operasional harian.</p>
        `,
        category: "Product",
        date: "2025-09-15",
        image: "/client/img/blog1.jpeg"
      }
    ];

    const article = articles.find(a => a.id === id);
    if(article){
      document.getElementById("title").textContent = article.title;
      document.getElementById("date").textContent = new Date(article.date).toLocaleDateString('id-ID',{year:'numeric',month:'long',day:'numeric'});
      document.getElementById("category").textContent = article.category;
      document.getElementById("image").src = article.image;
      document.getElementById("content").innerHTML = article.content;
    } else {
      document.getElementById("article").innerHTML = "<div class='p-6 text-center text-gray-500'>Artikel tidak ditemukan.</div>";
    }

    // Artikel terkait
    const related = document.getElementById("related");
    articles.filter(a => a.id != id).slice(0,2).forEach(a => {
      const div = document.createElement("div");
      div.className = "bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden";
      div.innerHTML = `
        <img src="${a.image}" class="w-full h-40 object-cover">
        <div class="p-4">
          <h3 class="font-semibold text-lg text-gray-900">
            <a href="berita?id=${a.id}" class="hover:text-blue-600">${a.title}</a>

          </h3>
          <p class="text-sm text-gray-600 mt-2">${a.excerpt}</p>
        </div>
      `;
      related.appendChild(div);
    });
</script>
@endsection
