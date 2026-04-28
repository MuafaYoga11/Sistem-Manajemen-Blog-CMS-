<!-- ═══════ MODAL: TAMBAH/EDIT PENULIS ═══════ -->
<div class="modal-overlay" id="modalPenulis">
    <div class="modal">
        <div class="modal-header">
            <h2 id="modalPenulisTitle">Tambah Penulis</h2>
            <button class="modal-close" onclick="closeModal('modalPenulis')"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <form id="formPenulis" onsubmit="return false;">
            <div class="modal-body">
                <input type="hidden" id="penulisId">
                <div class="form-group">
                    <label for="penulisNamaDepan">Nama Depan *</label>
                    <input type="text" class="form-control" id="penulisNamaDepan" placeholder="Masukkan nama depan" required>
                </div>
                <div class="form-group">
                    <label for="penulisNamaBelakang">Nama Belakang *</label>
                    <input type="text" class="form-control" id="penulisNamaBelakang" placeholder="Masukkan nama belakang" required>
                </div>
                <div class="form-group">
                    <label for="penulisUserName">Username *</label>
                    <input type="text" class="form-control" id="penulisUserName" placeholder="Masukkan username" required>
                </div>
                <div class="form-group">
                    <label for="penulisPassword">Password <span id="passwordHint">*</span></label>
                    <input type="password" class="form-control" id="penulisPassword" placeholder="Masukkan password">
                    <div class="form-hint" id="passwordFormHint" style="display:none;">Kosongkan jika tidak ingin mengubah password</div>
                </div>
                <div class="form-group">
                    <label>Foto Profil</label>
                    <div class="file-upload-area" id="penulisUploadArea">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p>Klik atau seret file ke sini</p>
                        <div class="file-name" id="penulisFileName"></div>
                        <input type="file" id="penuliFoto" accept="image/*" onchange="showFileName(this, 'penulisFileName')">
                    </div>
                    <div class="form-hint">Maks 2 MB. Format: JPG, PNG, GIF, WEBP. Kosongkan untuk foto default.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-batal" onclick="closeModal('modalPenulis')">Batal</button>
                <button type="button" class="btn-simpan" id="btnSimpanPenulis" onclick="simpanPenulis()">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════ MODAL: TAMBAH/EDIT ARTIKEL ═══════ -->
<div class="modal-overlay" id="modalArtikel">
    <div class="modal" style="width: 520px;">
        <div class="modal-header">
            <h2 id="modalArtikelTitle">Tambah Artikel</h2>
            <button class="modal-close" onclick="closeModal('modalArtikel')"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <form id="formArtikel" onsubmit="return false;">
            <div class="modal-body">
                <input type="hidden" id="artikelId">
                <div class="form-group">
                    <label for="artikelJudul">Judul Artikel *</label>
                    <input type="text" class="form-control" id="artikelJudul" placeholder="Masukkan judul artikel" required>
                </div>
                <div class="form-group">
                    <label for="artikelPenulis">Penulis *</label>
                    <select class="form-control" id="artikelPenulis" required>
                        <option value="">-- Pilih Penulis --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="artikelKategori">Kategori *</label>
                    <select class="form-control" id="artikelKategori" required>
                        <option value="">-- Pilih Kategori --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="artikelIsi">Isi Artikel *</label>
                    <textarea class="form-control" id="artikelIsi" rows="6" placeholder="Tulis isi artikel..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Gambar Artikel <span id="gambarHint">*</span></label>
                    <div class="file-upload-area" id="artikelUploadArea">
                        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p>Klik atau seret file gambar</p>
                        <div class="file-name" id="artikelFileName"></div>
                        <input type="file" id="artikelGambar" accept="image/*" onchange="showFileName(this, 'artikelFileName')">
                    </div>
                    <div class="form-hint">Maks 2 MB. Format: JPG, PNG, GIF, WEBP.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-batal" onclick="closeModal('modalArtikel')">Batal</button>
                <button type="button" class="btn-simpan" id="btnSimpanArtikel" onclick="simpanArtikel()">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════ MODAL: TAMBAH/EDIT KATEGORI ═══════ -->
<div class="modal-overlay" id="modalKategori">
    <div class="modal">
        <div class="modal-header">
            <h2 id="modalKategoriTitle">Tambah Kategori</h2>
            <button class="modal-close" onclick="closeModal('modalKategori')"><svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>
        <form id="formKategori" onsubmit="return false;">
            <div class="modal-body">
                <input type="hidden" id="kategoriId">
                <div class="form-group">
                    <label for="kategoriNama">Nama Kategori *</label>
                    <input type="text" class="form-control" id="kategoriNama" placeholder="Masukkan nama kategori" required>
                </div>
                <div class="form-group">
                    <label for="kategoriKeterangan">Keterangan</label>
                    <textarea class="form-control" id="kategoriKeterangan" rows="4" placeholder="Masukkan keterangan kategori"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-batal" onclick="closeModal('modalKategori')">Batal</button>
                <button type="button" class="btn-simpan" id="btnSimpanKategori" onclick="simpanKategori()">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════ MODAL: KONFIRMASI HAPUS ═══════ -->
<div class="modal-overlay modal-confirm" id="modalKonfirmasi">
    <div class="modal">
        <div class="confirm-icon">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
        </div>
        <div class="confirm-text">
            <h3>Hapus data ini?</h3>
            <p id="konfirmasiPesan">Data yang dihapus tidak dapat dikembalikan.</p>
        </div>
        <div class="confirm-footer">
            <button class="btn-batal" onclick="closeModal('modalKonfirmasi')">Batal</button>
            <button class="btn-ya-hapus" id="btnKonfirmasiHapus">Ya, Hapus</button>
        </div>
    </div>
</div>
