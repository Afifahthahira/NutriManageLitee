from flask import Flask, Response
import cv2
import torch

# Load YOLOv5 model
model = torch.hub.load('ultralytics/yolov5', 'yolov5s', pretrained=True)

app = Flask(__name__)

@app.route('/')
def home():
    return 'Welcome to the YOLOv5 Detection API! Access /detect to start detection.'

@app.route('/detect')
def detect():
    cap = cv2.VideoCapture(0)
    ret, frame = cap.read()
    if ret:
        # Object detection
        results = model(frame)

        # Convert frame to a format that can be displayed
        _, buffer = cv2.imencode('.jpg', results.render()[0])  # Render and encode the frame to JPG
        frame = buffer.tobytes()  # Convert buffer to bytes

    cap.release()
    return Response(frame, mimetype='image/jpeg')

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
