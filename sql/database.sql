CREATE DATABASE IF NOT EXISTS pengaduan_sarana;
USE pengaduan_sarana;

CREATE TABLE admin (
    id_admin INT(15) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_admin)
) ENGINE=InnoDB;

CREATE TABLE siswa (
    nis varchar(20) NOT NULL,
    kelas VARCHAR(10) NOT NULL,
    PRIMARY KEY (nis)
) ENGINE=InnoDB;

CREATE TABLE kategori (
    id_kategori INT(5) NOT NULL AUTO_INCREMENT,
    ket_kategori VARCHAR(30) NOT NULL,
    PRIMARY KEY (id_kategori)
) ENGINE=InnoDB;

CREATE TABLE input_aspirasi (
    id_pelaporan INT(5) NOT NULL AUTO_INCREMENT,
    nis varchar(20) NOT NULL,
    id_kategori INT(5) NOT NULL,
    lokasi VARCHAR(50) NOT NULL,
    ket VARCHAR(50) NOT NULL,
    tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_pelaporan),
    FOREIGN KEY (nis) REFERENCES siswa(nis)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE aspirasi (
    id_aspirasi INT(15) NOT NULL AUTO_INCREMENT,
    status ENUM('Menunggu','Proses','Selesai') NOT NULL DEFAULT 'Menunggu',
    id_pelaporan INT(5) NOT NULL,
    feedback TEXT,
    PRIMARY KEY (id_aspirasi),
    FOREIGN KEY (id_pelaporan) REFERENCES input_aspirasi(id_pelaporan)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;