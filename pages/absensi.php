<div class="tab-pane fade" id="absensi">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Input Absensi</h5>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Kelas</label>
                                            <select class="form-select">
                                                <option>7A</option>
                                                <option>7B</option>
                                                <option>8A</option>
                                                <option>8B</option>
                                                <option>9A</option>
                                                <option>9B</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Mata Pelajaran</label>
                                            <select class="form-select">
                                                <option>Matematika</option>
                                                <option>Bahasa Indonesia</option>
                                                <option>IPA</option>
                                                <option>IPS</option>
                                                <option>Bahasa Inggris</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Guru</label>
                                            <select class="form-select">
                                                <option>Bapak Ahmad</option>
                                                <option>Ibu Siti</option>
                                                <option>Bapak Budi</option>
                                                <option>Ibu Anita</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-striped" id="attendanceTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>101</td>
                                                <td>Andi Wijaya</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <input type="radio" class="btn-check" name="status1" id="hadir1" autocomplete="off" checked>
                                                        <label class="btn btn-outline-success" for="hadir1">Hadir</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status1" id="izin1" autocomplete="off">
                                                        <label class="btn btn-outline-warning" for="izin1">Izin</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status1" id="sakit1" autocomplete="off">
                                                        <label class="btn btn-outline-info" for="sakit1">Sakit</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status1" id="alpa1" autocomplete="off">
                                                        <label class="btn btn-outline-danger" for="alpa1">Alpa</label>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control form-control-sm" placeholder="keterangan"></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>102</td>
                                                <td>Budi Santoso</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <input type="radio" class="btn-check" name="status2" id="hadir2" autocomplete="off">
                                                        <label class="btn btn-outline-success" for="hadir2">Hadir</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status2" id="izin2" autocomplete="off" checked>
                                                        <label class="btn btn-outline-warning" for="izin2">Izin</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status2" id="sakit2" autocomplete="off">
                                                        <label class="btn btn-outline-info" for="sakit2">Sakit</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status2" id="alpa2" autocomplete="off">
                                                        <label class="btn btn-outline-danger" for="alpa2">Alpa</label>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control form-control-sm" placeholder="keterangan"></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>103</td>
                                                <td>Cindy Putri</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <input type="radio" class="btn-check" name="status3" id="hadir3" autocomplete="off">
                                                        <label class="btn btn-outline-success" for="hadir3">Hadir</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status3" id="izin3" autocomplete="off">
                                                        <label class="btn btn-outline-warning" for="izin3">Izin</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status3" id="sakit3" autocomplete="off">
                                                        <label class="btn btn-outline-info" for="sakit3">Sakit</label>
                                                        
                                                        <input type="radio" class="btn-check" name="status3" id="alpa3" autocomplete="off" checked>
                                                        <label class="btn btn-outline-danger" for="alpa3">Alpa</label>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control form-control-sm" placeholder="keterangan"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-3">
                                    <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Absensi</button>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Rekap Absensi</h5>
                                    <div class="d-flex">
                                        <input type="month" class="form-control me-2" style="width: 200px;">
                                        <button class="btn btn-secondary me-2"><i class="fas fa-filter"></i> Filter</button>
                                        <button class="btn btn-success"><i class="fas fa-file-excel"></i> Export</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th rowspan="2">NIS</th>
                                                <th rowspan="2">Nama</th>
                                                <th rowspan="2">Kelas</th>
                                                <th colspan="3" class="text-center">Jumlah</th>
                                                <th rowspan="2">Persentase</th>
                                            </tr>
                                            <tr>
                                                <th>Hadir</th>
                                                <th>Izin/Sakit</th>
                                                <th>Alpa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>101</td>
                                                <td>Andi Wijaya</td>
                                                <td>8A</td>
                                                <td>18</td>
                                                <td>1</td>
                                                <td>0</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 95%;" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">95%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>102</td>
                                                <td>Budi Santoso</td>
                                                <td>8A</td>
                                                <td>15</td>
                                                <td>3</td>
                                                <td>1</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>103</td>
                                                <td>Cindy Putri</td>
                                                <td>9B</td>
                                                <td>12</td>
                                                <td>2</td>
                                                <td>5</td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>