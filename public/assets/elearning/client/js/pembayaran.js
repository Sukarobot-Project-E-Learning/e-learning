// 🚧 DEBUG: hapus status beli setiap refresh (hapus setelah testing)
localStorage.removeItem("hasPurchased");

let chosenMethod = "";
let chosenBank = "";
let hasPurchased = false;

// ========================
// Jalankan saat halaman selesai dimuat
// ========================
document.addEventListener("DOMContentLoaded", () => {
  const storedPurchase = localStorage.getItem("hasPurchased");
  hasPurchased = storedPurchase === "true";
  if (hasPurchased) disableBuyButton();

  // === Event listener: metode pembayaran ===
  const paymentRadios = document.querySelectorAll(".payment-radio");
  if (paymentRadios.length > 0) {
    paymentRadios.forEach(radio => {
      radio.addEventListener("change", e => {
        chosenMethod = e.target.value;
        const bankOptions = document.getElementById("bankOptions");
        if (bankOptions) {
          bankOptions.classList.toggle("hidden", chosenMethod !== "Bank");
        }
      });
    });
  }

  // === Event listener: pilihan bank ===
  const bankRadios = document.querySelectorAll("input[name='bank']");
  if (bankRadios.length > 0) {
    bankRadios.forEach(bank => {
      bank.addEventListener("change", e => (chosenBank = e.target.value));
    });
  }

  // === Upload proof elements check ===
  const proofInput = document.getElementById("proofUpload");
  const submitBtn = document.getElementById("submitBtn");
  const uploadText = document.getElementById("uploadText");
  const fileIndicator = document.getElementById("fileIndicator");

  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.classList.add("bg-orange-300", "cursor-not-allowed");
  }

  if (proofInput) {
    proofInput.addEventListener("change", e => {
      if (e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        if (uploadText) uploadText.textContent = `📁 ${fileName}`;
        if (fileIndicator) fileIndicator.classList.remove("hidden");

        submitBtn.disabled = false;
        submitBtn.classList.remove("bg-orange-300", "cursor-not-allowed");
        submitBtn.classList.add("bg-orange-500", "hover:bg-orange-600");
      } else {
        if (uploadText) uploadText.textContent = "📂 Klik atau seret file ke sini";
        if (fileIndicator) fileIndicator.classList.add("hidden");

        submitBtn.disabled = true;
        submitBtn.classList.add("bg-orange-300", "cursor-not-allowed");
        submitBtn.classList.remove("bg-orange-500", "hover:bg-orange-600");
      }
    });
  }
});

// ========================
// Modal Pembayaran
// ========================
function openPaymentModal() {
  if (hasPurchased) return;

  let info = "";
  if (chosenMethod === "Bank" && chosenBank) {
    const [bank, acc, owner] = chosenBank.split("|");
    info = `<b>${bank}</b><br>No. Rekening: ${acc}<br>a.n ${owner}`;
  } else if (chosenMethod) {
    info = `<b>${chosenMethod}</b>`;
  }

  const infoBox = document.getElementById("paymentInfo");
  if (infoBox) infoBox.innerHTML = info || "Belum memilih metode";

  const modal = document.getElementById("paymentModal");
  if (modal) modal.classList.remove("hidden");
}

function closePaymentModal() {
  const modal = document.getElementById("paymentModal");
  if (modal) modal.classList.add("hidden");
}

// ========================
// Logika Konfirmasi Pembayaran
// ========================
function openUploadModal() {
  if (hasPurchased) return;

  if (chosenMethod === "Midtrans") {
    // langsung simulasi proses midtrans tanpa upload
    const modal = document.getElementById("paymentModal");
    if (modal) modal.classList.add("hidden");

    showAlertPopup("loading", chosenMethod);

    setTimeout(() => {
      showAlertPopup("success", chosenMethod);
      hasPurchased = true;
      localStorage.setItem("hasPurchased", "true");
      disableBuyButton();

      const status = document.getElementById("paymentStatus");
      if (status) {
        status.innerHTML = `
          <div class="border rounded-lg p-4 shadow-sm bg-green-50">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Status Pembayaran</h3>
            <div class="flex gap-3">
              <img src="https://via.placeholder.com/100x60" class="rounded-md">
              <div>
                <p class="font-semibold">SKILL BOOSTER: Kuasai Strategi Iklan Digital</p>
                <p class="text-xs text-gray-600">08 - 10 Oktober 2025, 19:00 WIB</p>
                <p class="mt-1 text-sm">
                  <span class="px-2 py-1 bg-green-200 text-green-800 rounded">Pembayaran Selesai</span>
                </p>
              </div>
            </div>
          </div>`;
      }
    }, 2000);
  } else {
    // jika manual / bank
    const paymentModal = document.getElementById("paymentModal");
    if (paymentModal) paymentModal.classList.add("hidden");

    const uploadModal = document.getElementById("uploadModal");
    if (uploadModal) uploadModal.classList.remove("hidden");
  }
}

// ========================
// Logika Upload Bukti Manual
// ========================
function submitProof() {
  const submitBtn = document.getElementById("submitBtn");
  if (!submitBtn || submitBtn.disabled || hasPurchased) return;

  const uploadModal = document.getElementById("uploadModal");
  if (uploadModal) uploadModal.classList.add("hidden");

  showAlertPopup("loading", "manual");

  setTimeout(() => {
    showAlertPopup("success", "manual");
    hasPurchased = true;
    localStorage.setItem("hasPurchased", "true");
    disableBuyButton();

    const status = document.getElementById("paymentStatus");
    if (status) {
      status.innerHTML = `
        <div class="border rounded-lg p-4 shadow-sm bg-yellow-50">
          <h3 class="text-sm font-semibold text-gray-700 mb-2">Status Pembayaran</h3>
          <div class="flex gap-3">
            <img src="https://via.placeholder.com/100x60" class="rounded-md">
            <div>
              <p class="font-semibold">SKILL BOOSTER: Kuasai Strategi Iklan Digital</p>
              <p class="text-xs text-gray-600">08 - 10 Oktober 2025, 19:00 WIB</p>
              <p class="mt-1 text-sm">
                <span class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded">Menunggu Konfirmasi Admin</span>
              </p>
            </div>
          </div>
        </div>`;
    }
  }, 2000);
}

// ========================
// Popup Notifikasi
// ========================
function showAlertPopup(type, method) {
    document.querySelectorAll(".custom-popup").forEach(p => p.remove());

    const popup = document.createElement("div");
    popup.className =
      "custom-popup fixed inset-0 flex items-center justify-center z-[9999] bg-[rgba(0,0,0,0.4)] backdrop-blur-sm animate-fadein";

    if (type === "loading") {
      popup.innerHTML = `
        <div class="bg-white p-6 rounded-2xl text-center shadow-xl animate-popup">
          <div class="mx-auto mb-4 w-12 h-12 border-4 border-orange-400 border-t-transparent rounded-full animate-spin"></div>
          <h3 class="text-lg font-bold text-gray-800">${
            method === "manual" ? "Mengunggah Bukti..." : "Memproses Pembayaran..."
          }</h3>
          <p class="text-sm text-gray-600 mt-1">Harap tunggu sebentar</p>
        </div>`;
    } else if (type === "success") {
      const title =
        method === "manual"
          ? "Upload Bukti Berhasil!"
          : "Pembayaran Berhasil!";
      const desc =
        method === "manual"
          ? "Menunggu konfirmasi admin."
          : "Transaksi selesai.";

      popup.innerHTML = `
        <div class="bg-white p-6 rounded-2xl text-center shadow-xl animate-popup">
          <svg class="checkmark mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
            <path class="checkmark__check" fill="none" d="M14 27l7 7 16-16" />
          </svg>
          <h3 class="text-lg font-bold text-gray-800">${title}</h3>
          <p class="text-sm text-gray-600 mt-1">${desc}</p>
          <button onclick="closeAlertPopup()" class="mt-4 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition">Tutup</button>
        </div>`;
    }

    document.body.appendChild(popup);
  }

  function closeAlertPopup() {
    document.querySelectorAll(".custom-popup").forEach(p => p.remove());
  }

// ========================
// Disable tombol beli
// ========================
function disableBuyButton() {
  const buyBtn = document.getElementById("buyButton");
  if (!buyBtn) return;
  buyBtn.disabled = true;
  buyBtn.classList.remove("bg-orange-500", "hover:bg-orange-600");
  buyBtn.classList.add("bg-gray-300", "text-gray-500", "cursor-not-allowed");
  buyBtn.innerText = "Sudah Dibeli";
}
