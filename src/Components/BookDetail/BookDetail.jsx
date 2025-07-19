import React from 'react';
import './BookDetail.css';
import Header from '../Header/Header';
import Footer from '../Footer/Footer';
import { useNavigate, useParams } from 'react-router-dom';

const dummyBooks = [
  {
    id: 1,
    title: 'Agama Negara & Masyarakat',
    category: 'Agama',
    author: 'Dr. A. Bakir Ihsan, Dr Cucu Nurhayati',
    year: 2020,
    pages: 199,
    publisher: 'Penerbit HAJA Mandiri',
    synopsis: 'Agama dan negara merupakan dua entitas yang merepresentasikan kuasa dalam kehidupan umat manusia. Agama wujud kuasa langit (Tuhan) dan negara simbol kuasa bumi (manusia). Tak jarang keduanya berkontestasi dalam hegemoni tahta, sering pula bersinergi dalam kolaborasi budaya. Negara yang menempatkan agama sebagai bagian penting dalam kehidupan warganya melahirkan relasi yang tidak sederhana. Negara dengan orientasi modernisasinya mendemarkasi agama sebagai kekuatan dalam beragam simbiosis baik mutualis, komensalis, maupun parasit.',
    image: '/images/agama1.jpeg',
    link: '/files/Agama1.pdf'
  },
  {
    id: 2,
    title: 'Pengantar Pendidikan Agama Islam',
    category: 'Agama',
    author: 'M. Ali Mahmudi, Syafruddin, Jumahir, Farid Haluti, Kuni Safingah, Taufik Abdillah Syukur, Isna Nurul Inayati, Sudirman',
    year: 2024,
    pages: 121,
    publisher: 'CV Hei Publishing Indonesia',
    synopsis: 'Relasi agama dan negara semakin menemukan ruang ekspresinya di alam demokrasi yang memungkinkan keterlibatan kaum agamawan (tokoh agama) dalam politik. Pada titik tertentu, negara memiliki kepentingan terhadap eksistensi kaum agamawan sebagai proxy dalam menyapa umat yang sekaligus warga negara',
    image: '/images/agama2.jpeg',
    link: '/files/Agama2.pdf'
  },
  {
    id: 3,
    title: 'Menyemai Toleransi Merawat NKRI',
    category: 'Agama',
    author: 'Dr. H.M. Zaki, S.Ag., M.Pd',
    year: 2018,
    pages: 256,
    publisher: 'Sanabil',
    synopsis: 'Pluralitas agama dan heterogenitas budaya menjadi ciri khas Bangsa Indonesia. Kedua entitas ini diyakini sebagai takdir. Ia tidak diminta, melainkan pemberian Tuhan Yang Maha Pencipta, bukan untuk ditawar tapi untuk diterima (taken for granted). Meski agama yang paling banyak dianut dan dijadikan sebagai pedoman oleh masyarakat Indonesia berjumlah enam: Islam, Kristen, Katolik, Hindu, Budha, dan Konghucu, namun keyakinan dan kepercayaan keagamaan sebagaian masyarakat Indonesia, juga diekspresikan dalam ratusan agama leluhur dan penghayat kepercayaan. ',
    image: '/images/agama3.jpg',
    link: '/files/Agama3.pdf'
  },
  {
    id: 4,
    title: 'Toleransi Beragama',
    category: 'Agama',
    author: 'Dr. Baidi Bukhori, S.Ag., M.Si',
    year: 2022,
    pages: 141,
    publisher: 'CV. Pilar Nusantara',
    synopsis: 'Keragaman hayati, bahasa, budaya, adat, suku, hingga agama yang dimiliki Indonesia meniscayakan adanya teloransi antar warga negara. Toleransi juga harus dimiliki oleh kelompok agama. Jika masing-masing kelompok agama tidak toleran terhadap kelompok agama lain maka akan menimbulkan konflik sosial bahkan pertumpahan darah.',
    image: '/images/agama4.jpeg',
    link: '/files/Agama4.pdf'
  },
  {
    id: 5,
    title: 'Internalisasi Nilai Toleransi Beragama di Masyarakat',
    category: 'Agama',
    author: 'Moch. Sya`roni Hasan, M.Pd.I.',
    year: 2018,
    pages: 140,
    publisher: 'CV. Kanaka Media',
    synopsis: 'Manusia sebagai makhluk sosial pasti mempunyai perbedaan, baik perbedaan dari segi kepribadiannya maupun dari segi sosialnya. Demikian juga dengan Bangsa Indonesia, yang memiliki pulau dari sabang sampai merauke terdiri atas pelbagai macam budaaya, suku, bahasa, budaya, ras dan agama. Beragam perbedaan itu tidak menghalangi para pendiri bangsa untuk bersatu padu menjalin persatuan serta kesatuan Bangsa Indonesia, sebagaimana tercermin dengan slogan Bhinneka Tunggal Ika. ',
    image: '/images/agama5.jpeg',
    link: '/files/Agama5.pdf'
  },
  {
    id: 6,
    title: 'Cantik itu Luka',
    category: 'Fiksi',
    author: 'Eka Kurniawan',
    year: 2004,
    pages: 494,
    publisher: 'PT Gramedia Pustaka utama',
    synopsis: 'ovel realisme magis yang menceritakan tragedi keluarga Dewi Ayu, seorang pelacur cantik, dan keturunan-keturunannya di kota Halimunda selama masa kolonial hingga pasca 1965. Dewi Ayu yang lahir dari pernikahan sedarah, kemudian menjadi pelacur karena kecantikannya, dan melahirkan empat anak perempuan yang mengalami nasib serupa. Cerita ini menggabungkan realisme dengan elemen magis, seperti kebangkitan Dewi Ayu dari kubur, dan menampilkan tema-tema seperti kekerasan, seksualitas, dan ketidakadilan sosial. ',
    image: '/images/fiksi1.jpg',
    link: '/files/Fiksi1.pdf'
  },
  {
    id: 7,
    title: 'Sang Pempimpi',
    category: 'Fiksi',
    author: 'Andrea Hirata',
    year: 2006,
    pages: 195,
    publisher: 'Penerbit Bentang',
    synopsis: 'menceritakan perjuangan tiga sahabat, Ikal, Arai, dan Jimbron, untuk meraih impian melanjutkan pendidikan ke jenjang tinggi di Perancis, meskipun berasal dari keluarga yang kurang mampu dan tinggal di Belitung. Ikal, Arai, dan Jimbron, yang bersekolah di SMA Negeri Manggar, berjuang keras untuk menabung dan bekerja sambil sekolah',
    image: '/images/fiksi2.jpg',
    link: '/files/Fiksi2.pdf'
  },
  {
    id: 8,
    title: 'Berani Bahagia',
    category: 'Fiksi',
    author: 'Ichiro Kishimi, Fumitake Koga',
    year: 2020,
    pages: 337,
    publisher: 'PT Gramedia Pustaka Utama',
    synopsis: 'Sang pemuda, yang kini sudah menjadi guru yang bertekad mem-praktikkan ide-ide Adler, menghubungi filsuf itu sekali lagi dan berkata: Teori psikologi Adler sebenarnya tak lebih dari se-kadar tumpukan teori kosong. Kau sedang berusaha menyesat-kan dan merusak generasi muda dengan ide-ide Adler. Aku harus melepaskan diri dari ide-ide berbahaya itu. Begitulah ujarnya. ',
    image: '/images/fiksi3.png',
    link:'/files/fiksi3.pdf'
  },
  {
    id: 9,
    title: 'Berjalan di Atas Air',
    category: 'Fiksi',
    author: 'Rahman Mangunssara',
    year: 2019,
    pages: 289,
    publisher: 'PT. Leutika Nouvalitera',
    synopsis: 'Novel yang ada di tangan Anda ini bukan cerita biasa, melainkan sebuah novel yang lahir dari pengalaman nyata sang penulis. Kisah masa kecil, peristiwa-peristiwa yang seru, menyentuh, hingga lucu, latar yang detail, di tangan jurnalis senior seperti Mas Rahman, terasa hidup dan seolah terjadi di depan mata kita. Ini kisah persahabatan sejati yang turut membangun karakter dan masa depan yang tak pernah terbayangkan sebelumnya. Sebuah novel yang menarik untuk siapa pun, dan akan relevan sepanjang usia kehidupan ini.',
    image: '/images/fiksi4.jpg',
    link: '/files/Fiksi4.pdf'
  },
  {
    id: 10,
    title: 'Laut Bercerita',
    category: 'Fiksi',
    author: 'Leila S. Chudori',
    year: 2025,
    pages: 330,
    publisher: 'PT. Gramedia Indonesia',
    synopsis: 'novel yang mengisahkan kisah nyata tentang sekelompok aktivis mahasiswa yang hilang pada tahun 1998, di masa Orde Baru. Novel ini bercerita tentang Biru Laut, seorang aktivis yang menjadi bagian dari perlawanan terhadap rezim otoriter, dan dampak hilangnya Biru Laut terhadap keluarganya, terutama adiknya, Asmara Jati, yang terus mencari kejelasan nasib kakaknya.',
    image: '/images/fiksi5.jpeg',
    link: '/files/Fiksi5.pdf'
  },
  {
    id: 11,
    title: 'Web Programming',
    category: 'Pemrograman',
    author: 'Ani Oktarini Sari, Ari Abdillah, Sunarti',
    year: 2019,
    pages: 99,
    publisher: 'Universitas Bina Sarana Informatika',
    synopsis: 'Buku Web Programing berisikan materi belajar mengenai dasar-dasar pemrograman web. Buku ini direkomendasikan bagi pemula belajar pemrograman web. Buku ini menjelaskan bagaimana belajar dasar-dasar web dengan mudah, praktis dan cepat disertakan contoh latihan-latihan. Dan adanya latihan contoh studi kasus membuat website yang responsive.',
    image: '/images/program1.jpg',
    link: '/files/Program1.pdf'
  },
  {
    id: 12,
    title: 'Belajar Pemrograman Web Dasar',
    category: 'Pemrograman',
    author: 'Dendy Kurniawan, S.Kom., M.Kom',
    year: 2022,
    pages: 235,
    publisher: 'Yayasan prima agus teknik',
    synopsis: 'Buku ini menjelaskan bagaimana belajar dasar-dasar web dengan mudah, praktis dan cepat disertakan contoh latihan-latihan.',
    image: '/images/program2.png',
    link: '/files/program2.pdf'
  },
  {
    id: 13,
    title: 'Mudah Membuat Web Bagi Pemula',
    category: 'Pemrograman',
    author: 'Moh Muthohir, S.Kom., M.Kom',
    year: 2025,
    pages: 450,
    publisher: 'Yayasan Prima Agus Teknik',
    synopsis: 'berisikan materi belajar mengenai dasar-dasar pemrograman web. Buku ini direkomendasikan bagi pemula belajar pemrograman web. Buku ini menjelaskan bagaimana belajar dasar-dasar web dengan mudah, praktis dan cepat disertakan contoh latihan-latihan. Dan adanya latihan contoh studi kasus membuat website ',
    image: '/images/program3.jpg',
    link: '/files/Program3.pdf'
  },
  {
    id: 14,
    title: 'Dasar pemrograman komputer dengan menggunakan matlab',
    category: 'Pemrograman',
    author: 'Trija Fayeldi, M.Si, Tatik Retno Murniasih, S.Si., M.Pd',
    year: 2025,
    pages: 480,
    publisher: 'Media Nusa Creative',
    synopsis: 'Buku Web Programing berisikan materi belajar mengenai dasar-dasar pemrograman web. Buku ini direkomendasikan bagi pemula belajar pemrograman web. Buku ini menjelaskan bagaimana belajar dasar-dasar web dengan mudah, praktis dan cepat disertakan contoh latihan-latihan. Dan adanya latihan contoh studi kasus membuat website yang responsive.',
    image: '/images/program4.jpg',
    link: '/files/Program4.pdf'
  },
  {
    id: 15,
    title: 'Pemrograman Komputer dengan dasar-dasar Python',
    category: 'Pemrograman',
    author: 'Ismah, M.Si',
    year: 2017,
    pages: 224,
    publisher: 'Fakultas Ilmu Pendidikan UMJ',
    synopsis: 'python merupakan salah satu bahasa pemrograman yang populer belakangan ini karena beberapa faktorfleksibelitas dapat digunakan di berbagai platform (Windows, Mac, Linux dan lain sebagainya). Bahasa yang dibangun sangat mudah dan sederhana, sehingga kesederhanaan bahasanya tersebut ada yang berpendapat bahwa Python merupakan salah satu Bahasa Pemrograman yang mendekati bahasa manusia. ',
    image: '/images/program5.jpeg',
    link: '/files/Program5.pdf'
  },
  {
  id: 16,
  title: 'Buku Pembelajaran Bola Volly',
  category: 'Olahraga',
  author: 'Dwi Yulia Nur Mulyadi, M',
  year: 2020,
  pages: 85,
  publisher: 'Penerbit M',
  synopsis: 'Ini sinopsis singkat untuk Buku Olahraga 1...',
  image: '/images/olahraga1.jpeg',
  link:'/files/Olahraga1.pdf'
},
{
  id: 17,
  title: 'Buku Pintar Sepakbola',
  category: 'Olahraga',
  author: 'Agus Salim',
  year: 2023,
  pages: 130,
  publisher: 'PT INTIMEDIA CIPTANUSANTARA',
  synopsis: 'Pada dasarnya sepakbola adalah olahraga yang memainkan bola dengan menggunakan kaki. Tujuan utamanya dari permainan ini adalah untuk mencetak gol atau skor sebanyak-banyaknya yang tentunya harus dilakukan sesuai dengan ketentuan yang telah ditetapkan. Untuk bisa membuat gol kalian harus tangkas, sigap, cepat dan baik dalam mengontrol bola',
  image:'/images/olahraga2.jpeg',
  link: '/files/Olahraga2.pdf'
},
{
  id: 18,
  title: 'Buku Ajar Bola Basket',
  category: 'Olahraga',
  author: 'Dr. Saichudin, M.Kes, Sayyid Agil Rifqi Munawar, S.Or',
  year: 2019,
  pages: 88,
  publisher: 'Penerbit Wineka Media',
  synopsis: 'Permainan bolabasket merupakan salah satu olahraga permainan bola besar berkelompok yang terdiri atas dua tim yang beranggotakan masing-masing lima orang dan saling bertanding untuk mencetak poin dengan memasukkan bola ke dalam keranjang lawan dan mencegah terjadinya poin ke keranjang sendiri.',
  image: '/images/olahraga3.jpeg',
  link: '/files/Olahraga3.pdf'
},
{
  id: 19,
  title: 'Olahraga Yoga',
  category: 'Olahraga',
  author: 'I Wayan Ambartana, S.K.M., M.Fis. Ni Made Yuni Gumala, S.K.M., M.Kes.',
  year: 2024,
  pages: 40,
  publisher: 'PT. Literasi Nusantara Abadi Grup',
  synopsis: 'Yoga (asthanga) sering digambarkan secara metaforis sebagai pohon dan terdiri dari delapan aspek, atau “anggota tubuh”. Patanjali mengkodifikasikan keajaiban yoga kuno sebagai asthanga yang merupakan salah satu dari enam aliran filsafat India dan dikenal sebagai Yoga Darshan Yama (etika universal), Niyama (etika individu), Asana (postur fisik), Pranayama (pengendalian napas), Pratyahara (pengendalian indra)',
  image: '/images/olahraga4.jpeg',
  link:'/files/Olahraga4.pdf'
},
{
  id: 20,
  title: 'Sejarah, Teknik & Variasa Latihan Tenis Meja',
  category: 'Olahraga',
  author: 'Guntur Firmansyah, Didik Hariyanto',
  year: 2019,
  pages: 99,
  publisher: 'Media Nusa Creative',
  synopsis: 'Tenis meja adalah cabang olahraga yang tempat bermainnya didalam ruangan/gedung (indoor game) yang dimainkan oleh 2 orang atau 4 orang yang langsung berhadapan diatas meja menggunakan net sebagai pembatasnya',
  image: '/images/olahraga5.png',
  link: '/files/Olahraga5.pdf'
},

];

const BookDetail = () => {
  const navigate = useNavigate();
  const { id } = useParams();

  const book = dummyBooks.find((b) => b.id === parseInt(id));

  if (!book) {
    return (
      <>
        <Header />
        <div className="book-detail-container">
          <div className="class-button">
            <button className="back-button" onClick={() => navigate(-1)}>&larr;</button>
            <h2>Buku tidak ditemukan</h2>
          </div>
        </div>
        <Footer />
      </>
    );
  }

  return (
    <>
      <Header />
      <div className="book-detail-container">
        <div className="class-button">
        <button className="class-button" onClick={() => navigate(-1)}>&larr; Kembali</button>
        <h2 className="book-title">{book.title}</h2>

        <div className="book-detail-content">
          <div className="book-image">
            <img src={book.image || '/assets/cover.jpg'} alt={`Cover ${book.title}`} />
          </div>
          <div className="book-info">
            <h3>{book.title}</h3>
            <p className="synopsis">
              Sinopsis: {book.synopsis || 'Sinopsis belum tersedia.'}
            </p>
            <hr />
            <div className="book-meta">
              <p>Penulis: <span>{book.author}</span></p>
              <p>Kategori: <span>{book.category}</span></p>
              <p>Tahun Terbit: <span>{book.year || 'Tidak diketahui'}</span></p>
              <p>Jumlah Halaman: <span>{book.pages || 'Tidak diketahui'}</span></p>
              <p>Penerbit: <span>{book.publisher || 'Tidak diketahui'}</span></p>
            </div>
            {book.link ? (
              <a href={book.link} target="_blank" rel="noopener noreferrer">
                <button className="btn-view">Lihat Buku</button>
              </a>
            ) : (
              <button className="btn-view" disabled>Link tidak tersedia</button>
            )}
          </div>
        </div>
      </div>
      </div>
      <Footer />
    </>
  );
};

export default BookDetail;
