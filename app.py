from flask import Flask, Response, jsonify
from flask_cors import CORS  # Impor CORS
import torch
import cv2
import numpy as np
import pathlib

temp = pathlib.PosixPath
pathlib.PosixPath = pathlib.WindowsPath

app = Flask(__name__)
CORS(app)  # Mengaktifkan CORS untuk semua route

model = torch.hub.load('ultralytics/yolov5', 'custom', path='best.pt')

def generate_frames():
    cap = cv2.VideoCapture(0)  # Gunakan kamera lokal
    while True:
        ret, frame = cap.read()
        if not ret:
            break
        # Deteksi menggunakan YOLOv5
        results = model(frame)
        annotated_frame = np.squeeze(results.render())  # Tambahkan hasil ke frame
        _, buffer = cv2.imencode('.jpg', annotated_frame)
        frame_bytes = buffer.tobytes()
        yield (b'--frame\r\n'
            b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')

@app.route('/video_feed')
def video_feed():
    return Response(generate_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

@app.route('/detect', methods=['POST'])
def detect():
    cap = cv2.VideoCapture(0)
    ret, frame = cap.read()
    if not ret:
        return jsonify({"error": "Unable to capture image"}), 400
    # Deteksi objek menggunakan model YOLOv5
    results = model(frame)
    objects_detected = len(results.xywh[0])  # Menghitung jumlah objek terdeteksi
    return jsonify({"jumlah_produk": objects_detected})

if __name__ == "__main__":
    app.run(debug=True)
