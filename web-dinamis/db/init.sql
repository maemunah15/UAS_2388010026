CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(15) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50) NOT NULL
);

INSERT INTO mahasiswa (nim, nama, jurusan) VALUES
('22010101', 'Achmad Fauzi', 'Teknik Informatika'),
('22010102', 'Siti Aminah', 'Sistem Informasi')
ON DUPLICATE KEY UPDATE nim=nim;
