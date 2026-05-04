@extends('client.main')

@section('css')
	<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program/detail-program.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/elearning/client/css/program/detail-kursus.css') }}">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('body')
	<div class="relative bg-slate-50 min-h-screen pb-28 lg:pb-16">
		<!-- Full width gradient hero background strictly for the top part -->
		<div class="absolute top-0 left-0 w-full h-[650px] lg:h-[550px] bg-gradient-to-br from-blue-50 via-purple-50 to-indigo-50 border-b border-gray-100 z-0"></div>

		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-28">
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
				
				<!-- KIRI: Hero Content + Lower Content -->
				<div class="lg:col-span-8 xl:col-span-8 space-y-8 pt-4 lg:pt-8 relative z-20">
					<!-- Hero area texts -->
					<div class="mb-12">
						<h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-6">{{ $program->program }}</h1>
						<p class="mt-4 text-lg text-slate-700 leading-relaxed max-w-3xl">{{ $program->description }}</p>

						@php
							$filledStars = max(0, min(5, (int) floor($avgRating ?? 0)));
							$hasHalfStar = (($avgRating ?? 0) - $filledStars) >= 0.5;
						@endphp

						<div class="mt-7 flex flex-wrap items-center gap-4 text-slate-700">
							<div class="flex items-center gap-2">
								<span class="font-bold text-base">{{ number_format($avgRating ?? 0, 1) }}</span>
								<div class="flex items-center gap-1">
									@for($star = 1; $star <= 5; $star++)
										@if($star <= $filledStars)
											<span class="text-amber-400">★</span>
										@elseif($hasHalfStar && $star === $filledStars + 1)
											<span class="text-amber-300">★</span>
										@else
											<span class="text-slate-300">★</span>
										@endif
									@endfor
								</div>
							</div>
							<span class="text-sm font-medium text-slate-500">{{ $totalRatings ?? 0 }} ulasan</span>
						</div>

						<div class="mt-6 flex flex-wrap items-center gap-3">
							<img src="{{ $program->instructor_avatar }}" alt="Creator" class="h-10 w-10 rounded-full border-2 border-white shadow-sm">
							<p class="text-slate-700 font-medium">
								Disusun oleh <span class="font-bold text-slate-900">{{ $program->instructor_name ?? 'Sukarobot Academy' }}</span>
							</p>
						</div>
                        
					</div>

					<!-- Kurikulum Kelas -->
					<div id="silabus-kelas" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 scroll-mt-32">
						<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
							<h2 class="font-bold text-xl text-gray-900">Kurikulum Kelas</h2>
						</div>

						<div class="space-y-4">
							@forelse($curriculumSections ?? [] as $section)
								<details class="group js-curriculum-details overflow-hidden rounded-2xl border border-slate-200 bg-white" {{ $loop->first ? 'open' : '' }}>
									<summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-4">
										<span class="text-xl font-bold text-gray-900">{{ $section['title'] ?? 'Bab Tanpa Judul' }}</span>
										<span class="text-xl font-bold text-blue-600 group-open:hidden">+</span>
										<span class="hidden text-xl font-bold text-blue-600 group-open:inline">−</span>
									</summary>

									<div class="js-curriculum-content border-t border-slate-200 px-5 py-4 space-y-3">
										@forelse($section['lessons'] ?? [] as $lesson)
											@php
												$previewUrl = route('client.program.classroom', ['slug' => $program->slug, 'item' => $lesson['index']]);
											@endphp
											<div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-3 last:border-0 last:pb-0">
												<div class="min-w-0 flex items-center gap-3">
													<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
													</svg>
													<p class="truncate text-xl font-semibold text-slate-900">{{ $lesson['title'] ?? 'Materi' }}</p>
												</div>

												@if($lesson['is_locked'] ?? true)
													<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0v4m-9 0h10a2 2 0 012 2v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4a2 2 0 012-2z" />
													</svg>
												@else
													<a href="{{ $previewUrl }}" class="inline-flex items-center gap-1 rounded-xl bg-blue-100 px-4 py-2 text-base font-semibold text-blue-700 hover:bg-blue-200">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
														</svg>
														Preview
													</a>
												@endif
											</div>
										@empty
											<p class="text-gray-500 text-sm">Belum ada materi di bab ini</p>
										@endforelse
									</div>
								</details>
							@empty
								<p class="text-gray-500 text-sm">Materi pembelajaran belum tersedia</p>
							@endforelse
						</div>
					</div>

					<!-- Tentang Instruktur -->
					<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
						<h3 class="font-bold text-xl text-gray-900 mb-4">Tentang Instruktur</h3>
						<div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
							<img src="{{ $program->instructor_avatar }}" alt="Instruktur" class="w-20 h-20 rounded-full border-4 border-blue-50 shadow-sm">
							<div>
								<h4 class="font-bold text-lg text-gray-900">{{ $program->instructor_name ?? 'Sukarobot Academy' }}</h4>
								<p class="text-blue-600 font-medium mt-1">{{($program->instructor_job ?? '') }}</p>
								<p class="text-gray-600 text-sm leading-relaxed mt-3">{{ $program->instructor_description ?? '' }}</p>
							</div>
						</div>
					</div>

				</div>

				<!-- KANAN: Sticky Sidebar -->
				<aside class="lg:col-span-4 xl:col-span-4 lg:mt-8 relative z-20">
					<div class="kursus-card bg-white rounded-2xl border border-purple-100 shadow-xl p-1 lg:sticky lg:top-28">
						<div class="bg-white rounded-xl p-4">
							@php
								$detailImageUrl = ($program->image && str_starts_with($program->image, 'images/'))
									? asset($program->image)
									: ($program->image ? asset('storage/' . $program->image) : asset('sukarobot.com/source/img/Sukarobot-logo.png'));
							@endphp

							<img src="{{ $detailImageUrl }}" alt="Poster Kelas" class="w-full rounded-xl object-cover border border-slate-100">

							@if(!$isPurchased)
								<div class="mt-8 mb-4">
									<p class="text-sm text-gray-500 mb-1">Harga {{ ucfirst($program->category) }}</p>
									@if($program->price > 0)
										<p class="text-3xl font-bold text-gray-900">Rp{{ number_format($program->price, 0, ',', '.') }}</p>
									@else
										<p class="text-3xl font-bold text-green-600">GRATIS</p>
									@endif
								</div>
							@endif

							<div class="{{ $isPurchased ? 'mt-4' : '' }}">
								@include('client.program.partials.purchase-cta-button', [
									'isPurchased' => $isPurchased,
									'isCourseProgram' => $isCourseProgram,
									'program' => $program,
								])
							</div>

							@if(!$isPurchased)
								@php
									$now = \Carbon\Carbon::now();
									$startDate = \Carbon\Carbon::parse($program->start_date);
									$endDate = \Carbon\Carbon::parse($program->end_date);
									$isRunning = $now->between($startDate, $endDate);
									$isFinished = $now->gt($endDate);
									$isSoldOut = $program->available_slots <= 0;
								@endphp

								@include('client.program.partials.purchase-status-buttons', [
									'isSoldOut' => $isSoldOut,
									'isCourseProgram' => $isCourseProgram,
									'isFinished' => $isFinished,
									'isRunning' => $isRunning,
									'program' => $program,
								])
							@endif

							<div class="mt-6 space-y-4 border-t border-slate-100 pt-6 text-sm">
								<div class="flex items-center justify-between gap-3">
									<span class="text-slate-500 font-medium">Total Siswa</span>
									<span class="font-bold text-slate-900">{{ number_format((int) ($program->enrolled_count ?? 0), 0, ',', '.') }}</span>
								</div>
								<div class="flex items-center justify-between gap-3">
									<span class="text-slate-500 font-medium">Skill Level</span>
									<span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">{{ $skillLevel }}</span>
								</div>
							</div>

							@include('client.program.partials.voucher-recommendations', [
								'recommendedVouchers' => $recommendedVouchers,
								'isPurchased' => $isPurchased,
							])

							<div class="mt-6 pt-5 border-t border-slate-100">
								<h3 class="font-bold mb-4 text-gray-900">Kamu Akan Mendapatkan:</h3>
								<div class="flex flex-wrap gap-2">
									@forelse($program->benefits as $benefit)
										<span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold border border-blue-100">{{ $benefit }}</span>
									@empty
										<span class="text-gray-500 text-sm">Belum ada benefit</span>
									@endforelse
								</div>
							</div>

							<div class="mt-6 pt-5 border-t border-slate-100">
								<h3 class="font-bold mb-4 text-gray-900">Tools yang Digunakan</h3>
								<div class="space-y-3">
									@forelse($program->tools as $tool)
										<div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
											<div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
												<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
												</svg>
											</div>
											<span class="text-sm font-bold text-gray-700">{{ $tool }}</span>
										</div>
									@empty
										<p class="text-gray-500 text-sm">Belum ada tools</p>
									@endforelse
								</div>
							</div>
						</div>
					</div>
				</aside>

			</div>
		</div>

		<div class="lg:hidden fixed inset-x-0 bottom-0 z-50 border-t border-slate-200 bg-white/95 px-4 py-3 backdrop-blur">
			<div class="mx-auto flex max-w-7xl items-center justify-between gap-3">
				<div class="min-w-0 flex-1">
					<p class="text-sm font-bold text-slate-900 truncate">{{ $program->program }}</p>
				</div>

				@if($isPurchased)
					<a href="{{ $isCourseProgram ? route('client.program.classroom', ['slug' => $program->slug]) : route('client.dashboard.program') }}"
						class="shrink-0 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
						{{ $isCourseProgram ? 'Masuk Kelas' : 'Lihat Kelas Saya' }}
					</a>
				@else
					@php
						$nowMobile = \Carbon\Carbon::now();
						$startDateMobile = \Carbon\Carbon::parse($program->start_date);
						$endDateMobile = \Carbon\Carbon::parse($program->end_date);
						$isRunningMobile = $nowMobile->between($startDateMobile, $endDateMobile);
						$isFinishedMobile = $nowMobile->gt($endDateMobile);
						$isSoldOutMobile = $program->available_slots <= 0;
					@endphp

					@if($isSoldOutMobile)
						<button type="button" disabled
							class="shrink-0 rounded-xl bg-red-400 px-5 py-3 text-sm font-bold text-white opacity-80 cursor-not-allowed">
							Kuota Habis
						</button>
					@elseif(!$isCourseProgram && $isFinishedMobile)
						<button type="button" disabled
							class="shrink-0 rounded-xl bg-gray-400 px-5 py-3 text-sm font-bold text-white opacity-80 cursor-not-allowed">
							Program Selesai
						</button>
					@elseif(!$isCourseProgram && $isRunningMobile)
						<button type="button" disabled
							class="shrink-0 rounded-xl bg-blue-300 px-5 py-3 text-sm font-bold text-white opacity-80 cursor-not-allowed">
							Pendaftaran Ditutup
						</button>
					@else
						<a href="{{ route('client.pembayaran', ['programSlug' => $program->slug]) }}"
							class="shrink-0 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-colors hover:bg-blue-700">
							Beli {{ ucfirst($program->category) }}
						</a>
					@endif
				@endif
			</div>
		</div>
	</div>
@endsection


@section('js')
	<script src="{{ asset('assets/elearning/client/js/program/detail-program.js') }}"></script>
@endsection
