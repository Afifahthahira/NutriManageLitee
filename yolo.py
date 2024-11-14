import torch
import cv2
import mysql.connector
from roboflow import Roboflow

# Inisialisasi Roboflow dengan API key yang kamu dapatkan
rf = Roboflow(api_key="MiTwsSw1r4kt1SzCVQfQ")  
project = rf.workspace("pcd-ryxhq").project("herbalife-product-detection")  
model = project.version(2).model  # Ganti dengan versi yang sesuai

# Fungsi untuk menghubungkan ke database MySQL
def connect_to_db():
    try:
        db = mysql.connector.connect(
            host="127.0.0.1",  # Alamat host MySQL, gunakan 127.0.0.1 atau localhost
            user="root",        # Nama pengguna MySQL (root adalah default di Laragon)
            password="",        # Password (kosong jika menggunakan Laragon default)
            database="db_nutrimanagenutri"  # Nama database yang sesuai
        )
        print("Koneksi MySQL berhasil!")
        return db
    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

# Fungsi untuk memperbarui jumlah produk dan nama produk di database
def update_product_stock(product_name, count):
    db = connect_to_db()
    if db:
        cursor = db.cursor()
        # Cek apakah produk sudah ada dalam database
        cursor.execute("SELECT * FROM stok_produk WHERE nama_produk = %s", (product_name,))
        result = cursor.fetchone()
        
        if result:
            # Jika produk sudah ada, tambahkan stoknya
            query = "UPDATE stok_produk SET stok = stok + %s WHERE nama_produk = %s"
            cursor.execute(query, (count, product_name))
        else:
            # Jika produk belum ada, masukkan data baru
            query = "INSERT INTO stok_produk (nama_produk, stok) VALUES (%s, %s)"
            cursor.execute(query, (product_name, count))
        
        db.commit()
        print(f"Produk '{product_name}' berhasil diperbarui: {count}")
        cursor.close()
        db.close()

def detect_and_update_stock():
    cap = cv2.VideoCapture(0)

    while cap.isOpened():
        ret, frame = cap.read()
        if not ret:
            print("Gagal menangkap gambar.")
            break

        # Deteksi objek menggunakan model Roboflow
        results = model.predict(frame)

        # Iterasi setiap prediksi dan gambar bounding box
        for prediction in results.predictions:
            # Dapatkan informasi bounding box
            x, y, width, height = prediction['x'], prediction['y'], prediction['width'], prediction['height']
            class_name = prediction['class']  # Nama kelas produk

            # Hitung koordinat untuk bounding box
            top_left = (int(x - width / 2), int(y - height / 2))
            bottom_right = (int(x + width / 2), int(y + height / 2))

            # Gambar bounding box dan label pada frame
            cv2.rectangle(frame, top_left, bottom_right, (255, 0, 0), 2)
            cv2.putText(frame, class_name, top_left, cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 1)

        # Tampilkan jumlah produk yang terdeteksi
        num_objects = len(results.predictions)
        cv2.putText(frame, f"Jumlah Produk: {num_objects}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 0, 0), 2, cv2.LINE_AA)

        # Tampilkan frame hasil deteksi
        cv2.imshow('Produk Detection', frame)

        # Memperbarui stok ketika tombol 'u' ditekan
        if cv2.waitKey(1) & 0xFF == ord('u'):
            for prediction in results.predictions:
                product_name = prediction['class']
                update_product_stock(product_name, 1)

        # Tekan 'q' untuk keluar dari aplikasi
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    cap.release()
    cv2.destroyAllWindows()
# Jalankan fungsi deteksi dan pembaruan stok
detect_and_update_stock()
