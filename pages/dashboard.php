<div class="tab-pane fade show active" id="dashboard">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-white bg-primary mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Siswa</h5>
                                                <h2 class="card-text">250</h2>
                                            </div>
                                            <i class="fas fa-users fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-success mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Hadir Hari Ini</h5>
                                                <h2 class="card-text">240</h2>
                                            </div>
                                            <i class="fas fa-check-circle fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-warning mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Izin</h5>
                                                <h2 class="card-text">5</h2>
                                            </div>
                                            <i class="fas fa-envelope fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-white bg-danger mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title">Alpa</h5>
                                                <h2 class="card-text">5</h2>
                                            </div>
                                            <i class="fas fa-times-circle fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Statistik Absensi Bulan Ini</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="attendanceChart" height="150"></canvas>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Absensi Terakhir</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Nama</th>
                                                        <th>Kelas</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo date('d-m-Y'); ?></td>
                                                        <td>Andi Wijaya</td>
                                                        <td>8A</td>
                                                        <td><span class="badge bg-success">Hadir</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo date('d-m-Y'); ?></td>
                                                        <td>Budi Santoso</td>
                                                        <td>8A</td>
                                                        <td><span class="badge bg-warning">Izin</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo date('d-m-Y'); ?></td>
                                                        <td>Cindy Putri</td>
                                                        <td>9B</td>
                                                        <td><span class="badge bg-danger">Alpa</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Kalender</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
