@extends('client.layout.main')

@section('css')
<!-- optional: add dashboard-specific css here -->
@endsection

@section('body')
@php
  // safe defaults
  $user = $user ?? (object)[ 'name'=>'Ruhiyat', 'email'=>'ruhiyat@example.com', 'phone'=>null, 'address'=>null ];
  $payments = $payments ?? [];
  $programs = $programs ?? [
    ['id'=>1,'type'=>'kursus','title'=>'Kursus Laravel untuk Pemula','status'=>'selesai','date'=>'2025-05-12'],
    ['id'=>2,'type'=>'pelatihan','title'=>'Pelatihan IoT Industri','status'=>'aktif','date'=>'2025-09-10'],
    ['id'=>3,'type'=>'sertifikasi','title'=>'Sertifikasi Cloud Engineer','status'=>'diikuti','date'=>'2025-07-01'],
    ['id'=>4,'type'=>'outing class','title'=>'Outing Class Robotics','status'=>'selesai','date'=>'2025-06-20'],
    ['id'=>5,'type'=>'outboard','title'=>'Outboard Workshop: Automotive','status'=>'aktif','date'=>'2025-10-01'],
  ];
  $certificates = $certificates ?? [];
  $vouchers = $vouchers ?? [];
  $notifications = $notifications ?? [];
@endphp

<div class="max-w-7xl mx-auto px-6 py-8">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
      <p class="text-sm text-slate-500 mt-1">Halo, <span class="font-medium">{{ $user->name ?? '' }}</span> — kelola akun dan programmu di sini.</p>
    </div>
    <div class="flex items-center gap-4">
      <button class="relative p-2 rounded-lg hover:bg-slate-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1" />
        </svg>
        <span class="absolute -top-1 -right-1 text-xs bg-orange-500 text-white px-1.5 py-0.5 rounded-full">{{ count($notifications) }}</span>
      </button>
      <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-blue-500 flex items-center justify-center text-white font-semibold">{{ strtoupper(substr($user->name ?? '',0,1)) }}</div>
        <div class="hidden sm:block text-right">
          <div class="text-sm font-medium">{{ $user->name ?? '' }}</div>
          <div class="text-xs text-slate-400">{{ $user->email ?? '' }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Sidebar -->
    <aside class="col-span-1 bg-white rounded-2xl p-4 border shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-blue-500 flex items-center justify-center text-white font-semibold text-lg">{{ strtoupper(substr($user->name ?? '',0,1)) }}</div>
        <div>
          <div class="font-medium text-sm">{{ $user->name ?? '' }}</div>
          <div class="text-xs text-slate-400">{{ $user->email ?? '' }}</div>
        </div>
      </div>

      <nav class="space-y-1">
        <button x-on:click.prevent="$dispatch('change-tab','profile')" class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-3 hover:bg-slate-50">👤 Profil</button>
        <button x-on:click.prevent="$dispatch('change-tab','programs')" class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-3 hover:bg-slate-50">📚 Program</button>
        <button x-on:click.prevent="$dispatch('change-tab','certificates')" class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-3 hover:bg-slate-50">📜 Sertifikat</button>
        <button x-on:click.prevent="$dispatch('change-tab','transactions')" class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-3 hover:bg-slate-50">💳 Riwayat Transaksi</button>
        <button x-on:click.prevent="$dispatch('change-tab','vouchers')" class="w-full text-left px-3 py-2 rounded-lg flex items-center gap-3 hover:bg-slate-50">🏷️ Voucher</button>
      </nav>
    </aside>

    <!-- Main content (tabs) -->
    <main class="col-span-3" x-data="dashboardTabs()" x-on:change-tab.window="tab = $event.detail">
      <div class="bg-white rounded-2xl p-6 border shadow-sm">
        <!-- PROFILE -->
        <div x-show="tab==='profile'" x-transition>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Profil Saya</h2>
            <div class="text-sm text-slate-400">Kelola informasi akun</div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1 flex flex-col items-center gap-3">
              <div class="w-28 h-28 rounded-full bg-gradient-to-br from-orange-400 to-blue-500 flex items-center justify-center text-white text-2xl">{{ strtoupper(substr($user->name ?? '',0,1)) }}</div>
            </div>
            <div class="md:col-span-2">
              <div class="space-y-2">
                <div><span class="text-xs text-slate-400">Nama</span><div class="font-medium">{{ $user->name ?? '' }}</div></div>
                <div><span class="text-xs text-slate-400">Email</span><div class="font-medium">{{ $user->email ?? '' }}</div></div>
                <div><span class="text-xs text-slate-400">Telepon</span><div class="font-medium">{{ $user->phone ?? '-' }}</div></div>
                <div><span class="text-xs text-slate-400">Alamat</span><div class="font-medium">{{ $user->address ?? '-' }}</div></div>
              </div>

              <div class="mt-4 flex gap-3">
                <button @click="editing=true" class="px-4 py-2 bg-orange-500 text-white rounded-lg">Edit Profil</button>
                <button @click="changePwd=true" class="px-4 py-2 bg-slate-100 rounded-lg">Ubah Kata Sandi</button>
              </div>

              <!-- Edit form modal-like area -->
              <div x-show="editing" x-transition class="mt-4 border-t pt-4">
                <form @submit.prevent="saveProfile" class="space-y-3">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input type="text" x-model="form.name" class="border rounded-lg px-3 py-2" placeholder="Nama" />
                    <input type="email" x-model="form.email" class="border rounded-lg px-3 py-2" placeholder="Email" />
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input type="text" x-model="form.phone" class="border rounded-lg px-3 py-2" placeholder="Telepon" />
                    <input type="text" x-model="form.address" class="border rounded-lg px-3 py-2" placeholder="Alamat" />
                  </div>
                  <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
                    <button type="button" @click="editing=false" class="px-4 py-2 bg-slate-100 rounded-lg">Batal</button>
                  </div>
                </form>
              </div>

              <!-- Change password -->
              <div x-show="changePwd" x-transition class="mt-4 border-t pt-4">
                <form @submit.prevent="changePassword" class="space-y-3 max-w-md">
                  <input type="password" x-model="pwd.old" placeholder="Kata sandi lama" class="border rounded-lg px-3 py-2 w-full" />
                  <input type="password" x-model="pwd.new" placeholder="Kata sandi baru" class="border rounded-lg px-3 py-2 w-full" />
                  <input type="password" x-model="pwd.confirm" placeholder="Konfirmasi kata sandi" class="border rounded-lg px-3 py-2 w-full" />
                  <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg">Simpan</button>
                    <button type="button" @click="changePwd=false" class="px-4 py-2 bg-slate-100 rounded-lg">Tutup</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- PROGRAMS -->
        <div x-show="tab==='programs'" x-transition class="space-y-4">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Program Saya</h2>
            <div class="text-sm text-slate-400">Filter berdasarkan tipe program</div>
          </div>
          <div class="flex items-center gap-3">
            <select x-model="programFilter" class="border rounded-lg px-3 py-2">
              <option value="all">Semua</option>
              <option value="kursus">Kursus</option>
              <option value="pelatihan">Pelatihan</option>
              <option value="sertifikasi">Sertifikasi</option>
              <option value="outing class">Outing Class</option>
              <option value="outboard">Outboard</option>
            </select>
            <div class="text-sm text-slate-500">Menemukan <strong x-text="filteredPrograms.length"></strong> program</div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-for="p in filteredPrograms" :key="p.id">
              <div class="bg-white border rounded-xl p-4 flex items-start gap-4">
                <div class="w-16 h-16 rounded-lg bg-slate-100 flex items-center justify-center text-xl font-semibold text-slate-600">@{{ p.type.charAt(0).toUpperCase() }}</div>
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <div>
                      <div class="font-semibold" x-text="p.title">Program Title</div>
                      <div class="text-xs text-slate-400" x-text="p.type">Type</div>
                    </div>
                    <div class="text-xs text-slate-500" x-text="p.status">status</div>
                  </div>
                  <div class="text-xs text-slate-400 mt-2">Mulai: <span x-text="p.date"></span></div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- CERTIFICATES -->
        <div x-show="tab==='certificates'" x-transition>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Sertifikat</h2>
            <div class="text-sm text-slate-400">Unduh sertifikat program yang telah diselesaikan</div>
          </div>
          <div class="space-y-3">
            <template x-for="c in certificates" :key="c.id">
              <div class="flex items-center justify-between bg-white border rounded-lg p-3">
                <div>
                  <div class="font-medium" x-text="c.program">Program Name</div>
                  <div class="text-xs text-slate-400">Diterbitkan: <span x-text="c.issued"></span></div>
                </div>
                <div class="flex items-center gap-2">
                  <a :href="c.file" class="px-3 py-1 rounded-lg bg-blue-600 text-white text-sm" target="_blank">Download</a>
                </div>
              </div>
            </template>
            <template x-if="certificates.length===0">
              <div class="text-slate-500 italic">Belum ada sertifikat untuk ditampilkan.</div>
            </template>
          </div>
        </div>

        <!-- TRANSACTIONS -->
        <div x-show="tab==='transactions'" x-transition>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Riwayat Transaksi</h2>
            <div class="text-sm text-slate-400">Semua transaksi yang pernah dilakukan</div>
          </div>
          <div class="space-y-3">
            <template x-for="t in transactions" :key="t.id">
              <div class="flex items-center justify-between bg-white border rounded-lg p-3">
                <div>
                  <div class="font-medium" x-text="t.title">Order</div>
                  <div class="text-xs text-slate-400">Tanggal: <span x-text="t.date"></span></div>
                </div>
                <div class="text-right">
                  <div class="font-medium">Rp <span x-text="formatNumber(t.amount)"></span></div>
                  <div :class="t.status==='pending'? 'text-orange-500 text-xs':'text-green-600 text-xs'" x-text="t.status"></div>
                </div>
              </div>
            </template>
          </div>
        </div>

        <!-- VOUCHERS -->
        <div x-show="tab==='vouchers'" x-transition>
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Voucher Saya</h2>
            <div class="text-sm text-slate-400">Lihat voucher yang sudah diklaim</div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <template x-for="v in vouchers" :key="v.code">
              <div class="bg-white border rounded-xl p-4 flex items-center justify-between">
                <div>
                  <div class="font-semibold" x-text="v.code">CODE</div>
                  <div class="text-xs text-slate-400">Diskon: <span x-text="v.discount"></span></div>
                </div>
                <div class="text-sm" :class="v.status==='used' ? 'text-green-600' : 'text-orange-600'" x-text="v.status"></div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- data for JS (base64-encoded JSON to avoid Blade/JS parsing issues) -->
<div id="dashboard-data" style="display:none"
     data-user="{{ base64_encode(json_encode($user)) }}"
     data-programs="{{ base64_encode(json_encode($programs)) }}"
     data-certificates="{{ base64_encode(json_encode($certificates)) }}"
     data-transactions="{{ base64_encode(json_encode($payments)) }}"
     data-vouchers="{{ base64_encode(json_encode($vouchers)) }}"></div>

<!-- Alpine JS -->
<script src="//unpkg.com/alpinejs" defer></script>
<script>
function dashboardTabs(){
  const el = document.getElementById('dashboard-data');
  const decode = s => { try{ return JSON.parse(atob(s)); } catch(e){ return null } };
  const userData = el ? decode(el.dataset.user || '') || {} : {};
  const programs = el ? decode(el.dataset.programs || '') || [] : [];
  const certificates = el ? decode(el.dataset.certificates || '') || [] : [];
  const transactions = el ? decode(el.dataset.transactions || '') || [] : [];
  const vouchers = el ? decode(el.dataset.vouchers || '') || [] : [];

  return {
    tab: 'profile',
    editing: false,
    changePwd: false,
    form: { name: userData.name || '', email: userData.email || '', phone: userData.phone || '', address: userData.address || '' },
    pwd: { old:'', new:'', confirm:'' },
    programFilter: 'all',
    programs: programs,
    certificates: certificates,
    transactions: transactions,
    vouchers: vouchers,
    get filteredPrograms(){ if(this.programFilter==='all') return this.programs; return this.programs.filter(p=>p.type===this.programFilter) },
    saveProfile(){ this.editing=false; alert('Profil disimpan (demo)') },
    changePassword(){ if(this.pwd.new!==this.pwd.confirm) return alert('Konfirmasi tidak cocok'); this.changePwd=false; alert('Kata sandi diubah (demo)') },
    previewCert(c){ window.open(c.file||'#','_blank') },
    formatNumber(n){ return new Intl.NumberFormat('id-ID').format(n) }
  }
}
</script>

@endsection
