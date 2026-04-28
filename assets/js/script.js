/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   UTILITY FUNCTIONS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
function esc(str) {
    if (!str) return '';
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const icon = type === 'success'
        ? '<svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
        : '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    toast.innerHTML = icon + '<span>' + esc(message) + '</span>';
    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('toast-out');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

function showFileName(input, targetId) {
    const el = document.getElementById(targetId);
    el.textContent = input.files.length > 0 ? input.files[0].name : '';
}

function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = '';
}

function setLoading(btnId, loading) {
    const btn = document.getElementById(btnId);
    if (loading) {
        btn.disabled = true;
        btn.dataset.originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner"></span> Menyimpan...';
    } else {
        btn.disabled = false;
        btn.innerHTML = btn.dataset.originalHtml;
    }
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   SECTION NAVIGATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
function showSection(name) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));

    document.getElementById('section' + name.charAt(0).toUpperCase() + name.slice(1)).classList.add('active');
    document.querySelector(`.nav-item[data-section="${name}"]`).classList.add('active');

    if (name === 'penulis') muatPenulis();
    else if (name === 'artikel') muatArtikel();
    else if (name === 'kategori') muatKategori();
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   BADGE COLOR HELPER
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
const badgeColors = ['badge-primary', 'badge-success', 'badge-warning', 'badge-danger', 'badge-info'];
function getBadgeColor(id) {
    return badgeColors[(id || 0) % badgeColors.length];
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   PENULIS CRUD
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
function muatPenulis() {
    fetch('ambil_penulis.php')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('tabelPenulis');
            if (res.status !== 'success' || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="empty-state"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg><p>Belum ada data penulis</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.data.map(p => {
                const namaLengkap = esc(p.nama_depan) + ' ' + esc(p.nama_belakang);
                const maskedPw = '••••••••';
                return `<tr>
                    <td><img src="uploads_penulis/${esc(p.foto)}" alt="Foto" class="table-thumbnail" onerror="this.src='uploads_penulis/default.png'"></td>
                    <td><strong>${namaLengkap}</strong></td>
                    <td><span class="badge badge-primary">@${esc(p.user_name)}</span></td>
                    <td><span class="password-mask">${maskedPw}</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-edit" onclick="editPenulis(${p.id})" title="Edit">Edit</button>
                            <button class="btn-hapus" onclick="konfirmasiHapusPenulis(${p.id}, '${esc(namaLengkap)}')" title="Hapus">Hapus</button>
                        </div>
                    </td>
                </tr>`;
            }).join('');
        })
        .catch(() => showToast('Gagal memuat data penulis', 'error'));
}

function openTambahPenulis() {
    document.getElementById('modalPenulisTitle').textContent = 'Tambah Penulis';
    document.getElementById('penulisId').value = '';
    document.getElementById('penulisNamaDepan').value = '';
    document.getElementById('penulisNamaBelakang').value = '';
    document.getElementById('penulisUserName').value = '';
    document.getElementById('penulisPassword').value = '';
    document.getElementById('penuliFoto').value = '';
    document.getElementById('penulisFileName').textContent = '';
    document.getElementById('passwordHint').textContent = '*';
    document.getElementById('passwordFormHint').style.display = 'none';
    document.getElementById('penulisPassword').required = true;
    openModal('modalPenulis');
}

function editPenulis(id) {
    fetch('ambil_satu_penulis.php?id=' + id)
        .then(r => r.json())
        .then(res => {
            if (res.status !== 'success') {
                showToast(res.message, 'error');
                return;
            }
            const d = res.data;
            document.getElementById('modalPenulisTitle').textContent = 'Edit Penulis';
            document.getElementById('penulisId').value = d.id;
            document.getElementById('penulisNamaDepan').value = d.nama_depan;
            document.getElementById('penulisNamaBelakang').value = d.nama_belakang;
            document.getElementById('penulisUserName').value = d.user_name;
            document.getElementById('penulisPassword').value = '';
            document.getElementById('penuliFoto').value = '';
            document.getElementById('penulisFileName').textContent = '';
            document.getElementById('passwordHint').textContent = '(opsional)';
            document.getElementById('passwordFormHint').style.display = 'block';
            document.getElementById('penulisPassword').required = false;
            openModal('modalPenulis');
        })
        .catch(() => showToast('Gagal mengambil data penulis', 'error'));
}

function simpanPenulis() {
    const id   = document.getElementById('penulisId').value;
    const isEdit = id !== '';

    const namaDepan    = document.getElementById('penulisNamaDepan').value.trim();
    const namaBelakang = document.getElementById('penulisNamaBelakang').value.trim();
    const userName     = document.getElementById('penulisUserName').value.trim();
    const password     = document.getElementById('penulisPassword').value.trim();

    if (!namaDepan || !namaBelakang || !userName) {
        showToast('Nama depan, nama belakang, dan username wajib diisi', 'error');
        return;
    }
    if (!isEdit && !password) {
        showToast('Password wajib diisi', 'error');
        return;
    }

    const fd = new FormData();
    if (isEdit) fd.append('id', id);
    fd.append('nama_depan', namaDepan);
    fd.append('nama_belakang', namaBelakang);
    fd.append('user_name', userName);
    if (password) fd.append('password', password);

    const fotoInput = document.getElementById('penuliFoto');
    if (fotoInput.files.length > 0) {
        fd.append('foto', fotoInput.files[0]);
    }

    const url = isEdit ? 'update_penulis.php' : 'simpan_penulis.php';
    setLoading('btnSimpanPenulis', true);

    fetch(url, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            setLoading('btnSimpanPenulis', false);
            if (res.status === 'success') {
                showToast(res.message, 'success');
                closeModal('modalPenulis');
                muatPenulis();
            } else {
                showToast(res.message, 'error');
            }
        })
        .catch(() => {
            setLoading('btnSimpanPenulis', false);
            showToast('Terjadi kesalahan jaringan', 'error');
        });
}

function konfirmasiHapusPenulis(id, nama) {
    document.getElementById('konfirmasiPesan').textContent = `Apakah Anda yakin ingin menghapus penulis "${nama}"?`;
    document.getElementById('btnKonfirmasiHapus').onclick = () => hapusPenulis(id);
    openModal('modalKonfirmasi');
}

function hapusPenulis(id) {
    fetch('hapus_penulis.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(res => {
        closeModal('modalKonfirmasi');
        if (res.status === 'success') {
            showToast(res.message, 'success');
            muatPenulis();
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(() => showToast('Gagal menghapus penulis', 'error'));
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   KATEGORI CRUD
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
function muatKategori() {
    fetch('ambil_kategori.php')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('tabelKategori');
            if (res.status !== 'success' || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="empty-state"><svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg><p>Belum ada data kategori</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.data.map(k => `<tr>
                <td><span class="badge badge-kategori">${esc(k.nama_kategori)}</span></td>
                <td>${esc(k.keterangan) || '<span style="color:#aaa">-</span>'}</td>
                <td>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="editKategori(${k.id})" title="Edit">Edit</button>
                        <button class="btn-hapus" onclick="konfirmasiHapusKategori(${k.id}, '${esc(k.nama_kategori)}')" title="Hapus">Hapus</button>
                    </div>
                </td>
            </tr>`).join('');
        })
        .catch(() => showToast('Gagal memuat data kategori', 'error'));
}

function openTambahKategori() {
    document.getElementById('modalKategoriTitle').textContent = 'Tambah Kategori';
    document.getElementById('kategoriId').value = '';
    document.getElementById('kategoriNama').value = '';
    document.getElementById('kategoriKeterangan').value = '';
    openModal('modalKategori');
}

function editKategori(id) {
    fetch('ambil_satu_kategori.php?id=' + id)
        .then(r => r.json())
        .then(res => {
            if (res.status !== 'success') {
                showToast(res.message, 'error');
                return;
            }
            const d = res.data;
            document.getElementById('modalKategoriTitle').textContent = 'Edit Kategori';
            document.getElementById('kategoriId').value = d.id;
            document.getElementById('kategoriNama').value = d.nama_kategori;
            document.getElementById('kategoriKeterangan').value = d.keterangan || '';
            openModal('modalKategori');
        })
        .catch(() => showToast('Gagal mengambil data kategori', 'error'));
}

function simpanKategori() {
    const id   = document.getElementById('kategoriId').value;
    const isEdit = id !== '';
    const nama = document.getElementById('kategoriNama').value.trim();
    const ket  = document.getElementById('kategoriKeterangan').value.trim();

    if (!nama) {
        showToast('Nama kategori wajib diisi', 'error');
        return;
    }

    const payload = { nama_kategori: nama, keterangan: ket };
    if (isEdit) payload.id = parseInt(id);

    const url = isEdit ? 'update_kategori.php' : 'simpan_kategori.php';
    setLoading('btnSimpanKategori', true);

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
        setLoading('btnSimpanKategori', false);
        if (res.status === 'success') {
            showToast(res.message, 'success');
            closeModal('modalKategori');
            muatKategori();
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(() => {
        setLoading('btnSimpanKategori', false);
        showToast('Terjadi kesalahan jaringan', 'error');
    });
}

function konfirmasiHapusKategori(id, nama) {
    document.getElementById('konfirmasiPesan').textContent = `Apakah Anda yakin ingin menghapus kategori "${nama}"?`;
    document.getElementById('btnKonfirmasiHapus').onclick = () => hapusKategori(id);
    openModal('modalKonfirmasi');
}

function hapusKategori(id) {
    fetch('hapus_kategori.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(res => {
        closeModal('modalKonfirmasi');
        if (res.status === 'success') {
            showToast(res.message, 'success');
            muatKategori();
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(() => showToast('Gagal menghapus kategori', 'error'));
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   ARTIKEL CRUD
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
function muatArtikel() {
    fetch('ambil_artikel.php')
        .then(r => r.json())
        .then(res => {
            const tbody = document.getElementById('tabelArtikel');
            if (res.status !== 'success' || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="empty-state"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><p>Belum ada data artikel</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.data.map(a => `<tr>
                <td><img src="uploads_artikel/${esc(a.gambar)}" alt="Gambar" class="table-thumbnail" onerror="this.style.display='none'"></td>
                <td><strong>${esc(a.judul)}</strong></td>
                <td><span class="badge badge-kategori">${esc(a.nama_kategori)}</span></td>
                <td>${esc(a.nama_penulis)}</td>
                <td style="white-space:nowrap;font-size:12px;color:#888">${esc(a.hari_tanggal)}</td>
                <td>
                    <div class="action-btns">
                        <button class="btn-edit" onclick="editArtikel(${a.id})" title="Edit">Edit</button>
                        <button class="btn-hapus" onclick="konfirmasiHapusArtikel(${a.id}, '${esc(a.judul)}')" title="Hapus">Hapus</button>
                    </div>
                </td>
            </tr>`).join('');
        })
        .catch(() => showToast('Gagal memuat data artikel', 'error'));
}

function loadDropdowns() {
    // Load penulis dropdown
    fetch('ambil_penulis.php')
        .then(r => r.json())
        .then(res => {
            const sel = document.getElementById('artikelPenulis');
            const current = sel.value;
            sel.innerHTML = '<option value="">-- Pilih Penulis --</option>';
            if (res.status === 'success') {
                res.data.forEach(p => {
                    sel.innerHTML += `<option value="${p.id}">${esc(p.nama_depan)} ${esc(p.nama_belakang)}</option>`;
                });
            }
            if (current) sel.value = current;
        });

    // Load kategori dropdown
    fetch('ambil_kategori.php')
        .then(r => r.json())
        .then(res => {
            const sel = document.getElementById('artikelKategori');
            const current = sel.value;
            sel.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            if (res.status === 'success') {
                res.data.forEach(k => {
                    sel.innerHTML += `<option value="${k.id}">${esc(k.nama_kategori)}</option>`;
                });
            }
            if (current) sel.value = current;
        });
}

function openTambahArtikel() {
    document.getElementById('modalArtikelTitle').textContent = 'Tambah Artikel';
    document.getElementById('artikelId').value = '';
    document.getElementById('artikelJudul').value = '';
    document.getElementById('artikelIsi').value = '';
    document.getElementById('artikelGambar').value = '';
    document.getElementById('artikelFileName').textContent = '';
    document.getElementById('gambarHint').textContent = '*';
    document.getElementById('artikelGambar').required = true;
    loadDropdowns();
    setTimeout(() => {
        document.getElementById('artikelPenulis').value = '';
        document.getElementById('artikelKategori').value = '';
    }, 300);
    openModal('modalArtikel');
}

function editArtikel(id) {
    loadDropdowns();
    fetch('ambil_satu_artikel.php?id=' + id)
        .then(r => r.json())
        .then(res => {
            if (res.status !== 'success') {
                showToast(res.message, 'error');
                return;
            }
            const d = res.data;
            document.getElementById('modalArtikelTitle').textContent = 'Edit Artikel';
            document.getElementById('artikelId').value = d.id;
            document.getElementById('artikelJudul').value = d.judul;
            document.getElementById('artikelIsi').value = d.isi;
            document.getElementById('artikelGambar').value = '';
            document.getElementById('artikelFileName').textContent = '';
            document.getElementById('gambarHint').textContent = '(opsional)';
            document.getElementById('artikelGambar').required = false;

            // Set dropdowns after they load
            setTimeout(() => {
                document.getElementById('artikelPenulis').value = d.id_penulis;
                document.getElementById('artikelKategori').value = d.id_kategori;
            }, 400);

            openModal('modalArtikel');
        })
        .catch(() => showToast('Gagal mengambil data artikel', 'error'));
}

function simpanArtikel() {
    const id   = document.getElementById('artikelId').value;
    const isEdit = id !== '';

    const judul      = document.getElementById('artikelJudul').value.trim();
    const idPenulis  = document.getElementById('artikelPenulis').value;
    const idKategori = document.getElementById('artikelKategori').value;
    const isi        = document.getElementById('artikelIsi').value.trim();
    const gambarFile = document.getElementById('artikelGambar').files[0];

    if (!judul || !idPenulis || !idKategori || !isi) {
        showToast('Judul, penulis, kategori, dan isi wajib diisi', 'error');
        return;
    }
    if (!isEdit && !gambarFile) {
        showToast('Gambar artikel wajib diupload', 'error');
        return;
    }

    const fd = new FormData();
    if (isEdit) fd.append('id', id);
    fd.append('judul', judul);
    fd.append('id_penulis', idPenulis);
    fd.append('id_kategori', idKategori);
    fd.append('isi', isi);
    if (gambarFile) fd.append('gambar', gambarFile);

    const url = isEdit ? 'update_artikel.php' : 'simpan_artikel.php';
    setLoading('btnSimpanArtikel', true);

    fetch(url, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            setLoading('btnSimpanArtikel', false);
            if (res.status === 'success') {
                showToast(res.message, 'success');
                closeModal('modalArtikel');
                muatArtikel();
            } else {
                showToast(res.message, 'error');
            }
        })
        .catch(() => {
            setLoading('btnSimpanArtikel', false);
            showToast('Terjadi kesalahan jaringan', 'error');
        });
}

function konfirmasiHapusArtikel(id, judul) {
    document.getElementById('konfirmasiPesan').textContent = `Apakah Anda yakin ingin menghapus artikel "${judul}"?`;
    document.getElementById('btnKonfirmasiHapus').onclick = () => hapusArtikel(id);
    openModal('modalKonfirmasi');
}

function hapusArtikel(id) {
    fetch('hapus_artikel.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    })
    .then(r => r.json())
    .then(res => {
        closeModal('modalKonfirmasi');
        if (res.status === 'success') {
            showToast(res.message, 'success');
            muatArtikel();
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(() => showToast('Gagal menghapus artikel', 'error'));
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   INIT — load default section
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
document.addEventListener('DOMContentLoaded', () => {
    muatPenulis();
});
