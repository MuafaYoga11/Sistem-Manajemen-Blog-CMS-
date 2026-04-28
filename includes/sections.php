<!-- ═══════════════════════
     SECTION: PENULIS
═════════════════════════ -->
<div class="section active" id="sectionPenulis">
    <div class="section-header">
        <div class="section-title">
            Kelola Penulis
        </div>
        <button class="btn-tambah" onclick="openTambahPenulis()" id="btnTambahPenulis">
            + Tambah Penulis
        </button>
    </div>
    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelPenulis">
                    <tr><td colspan="5" class="empty-state"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><p>Memuat data...</p></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══════════════════════
     SECTION: ARTIKEL
═════════════════════════ -->
<div class="section" id="sectionArtikel">
    <div class="section-header">
        <div class="section-title">
            Kelola Artikel
        </div>
        <button class="btn-tambah" onclick="openTambahArtikel()" id="btnTambahArtikel">
            + Tambah Artikel
        </button>
    </div>
    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelArtikel">
                    <tr><td colspan="6" class="empty-state"><p>Memuat data...</p></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══════════════════════
     SECTION: KATEGORI
═════════════════════════ -->
<div class="section" id="sectionKategori">
    <div class="section-header">
        <div class="section-title">
            Kelola Kategori Artikel
        </div>
        <button class="btn-tambah" onclick="openTambahKategori()" id="btnTambahKategori">
            + Tambah Kategori
        </button>
    </div>
    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelKategori">
                    <tr><td colspan="3" class="empty-state"><p>Memuat data...</p></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
