<div class="tab-pane fade" id="laporan">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Cetak Laporan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form>
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Laporan</label>
                                                <select class="form-select">
                                                    <option>Rekap Absensi Siswa</option>
                                                    <option>Rekap Absensi per Kelas</option>
                                                    <option>Rekap Absensi Bulanan</option>
                                                    <option>Rekap Absensi Semester</option>
                                                    <option>Daftar Siswa</option>
                                                    <option>Daftar Guru</option>
                                                </select>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Dari Tanggal</label>
                                                    <input type="date" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Sampai Tanggal</label>
                                                    <input type="date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Kelas (opsional)</label>
                                                <select class="form-select">
                                                    <option value="">Semua Kelas</option>
                                                    <option>7A</option>
                                                    <option>7B</option>
                                                    <option>8A</option>
                                                    <option>8B</option>
                                                    <option>9A</option>
                                                    <option>9B</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Cetak PDF</button>
                                            <button type="submit" class="btn btn-success ms-2"><i class="fas fa-file-excel"></i> Export Excel</button>
                                        </form>
                                    </div>
                                    <div class="col-md-6 text-center">
                                    <img src="https://placehold.co/400x300" alt="Ilustrasi laporan absensi dengan grafik, tabel data, dan komputer" class="img-fluid rounded">
                                        <p class="mt-2 text-muted">Pilih parameter laporan untuk mencetak atau mengekspor data</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>