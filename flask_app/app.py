from flask import Flask, request, jsonify, render_template, send_from_directory
from flask_cors import CORS  # Import CORS
import os
from ultralytics import YOLO
import shutil
import cv2
import numpy as np

app = Flask(__name__)
CORS(app)  # Enable CORS

# Load model here
model = YOLO('run14_best.pt')
model_cls_vegetable = YOLO('run12_cls_best_vegetable.pt')
model_cls_meat = YOLO('run2_cls_best_meat.pt')
model_cls_fruit = YOLO('run10_cls_best_fruit.pt')
UPLOAD_FOLDER = 'uploads'
PROCESSED_FOLDER = 'processed'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(PROCESSED_FOLDER, exist_ok=True)

detected_class = set()

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        print('No file part')
        return jsonify({'error': 'No file part'})
    file = request.files['file']
    if file.filename == '':
        print('No selected file')
        return jsonify({'error': 'No selected file'})
    if file:
        filepath = os.path.join(UPLOAD_FOLDER, file.filename)
        file.save(filepath)
        processed_image_path = process_image(filepath)
        print({'image_url': f'/processed/{os.path.basename(processed_image_path)}'})
        return jsonify({'image_url': f'/processed/{os.path.basename(processed_image_path)}'})

@app.route('/detections', methods=['GET'])
def get_detections():
    print(f'Detected items to send: {detected_class}')  # Debug output
    return jsonify(list(detected_class))

@app.route('/processed/<filename>')
def serve_processed_file(filename):
    return send_from_directory(PROCESSED_FOLDER, filename)

def process_image(image_path):
    global detected_class
    detected_class = set()
    
    subfolder_path = os.path.join(UPLOAD_FOLDER, 'detect')
    if os.path.exists(subfolder_path):
        shutil.rmtree(subfolder_path)
    os.makedirs(subfolder_path, exist_ok=True)
    
    results = model(image_path)
    
    save_dir = 'uploads/detect/'
    os.makedirs(save_dir, exist_ok=True)
    names = model.names
    cls_names_vegetable = model_cls_vegetable.names
    cls_names_meat = model_cls_meat.names
    cls_names_fruit = model_cls_fruit.names
    
    img = cv2.imread(image_path)
    
    for result in results:
        for box in result.boxes:
            x1, y1, x2, y2 = map(int, box.xyxy[0])
            detected_region = img[y1:y2, x1:x2]
            
            label = names[int(box.cls)]
            if label == 'Vegetable':
                cls_result = model_cls_vegetable.predict(source=detected_region)
                for r in cls_result:
                    label = cls_names_vegetable[r.probs.top1]
            elif label == 'Meat':
                cls_result = model_cls_meat.predict(source=detected_region)
                for r in cls_result:
                    label = cls_names_meat[r.probs.top1]
            elif label == 'Fruit':
                cls_result = model_cls_fruit.predict(source=detected_region)
                for r in cls_result:
                    label = cls_names_fruit[r.probs.top1]
            
            detected_class.add(label)
            print(f'Detected: {label}')  # Debug output for detected labels
            
            # Generate a random color for each box
            color = tuple(np.random.randint(0, 255, size=3).tolist())
            
            # Draw the bounding box and label on the image
            cv2.rectangle(img, (x1, y1), (x2, y2), (0,255,0), 2)
            cv2.putText(img, label, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0,255,0), 2)
    
    print(f'Final detected_class set: {detected_class}')  # Debug output for final detected set
    processed_image_path = os.path.join(PROCESSED_FOLDER, os.path.basename(image_path))
    cv2.imwrite(processed_image_path, img)
    
    return processed_image_path

if __name__ == '__main__':
    app.run(debug=True)
