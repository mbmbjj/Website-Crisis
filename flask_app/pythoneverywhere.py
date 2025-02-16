from flask import Flask, request, jsonify, render_template, send_from_directory
from flask_cors import CORS
import os
from ultralytics import YOLO
import shutil
import cv2
import uuid

app = Flask(__name__)
CORS(app)

# Define the base directory
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Load model here with absolute paths
model = YOLO(os.path.join(BASE_DIR, 'run14_best.pt'))
model_cls_vegetable = YOLO(os.path.join(BASE_DIR, 'run12_cls_best_vegetable.pt'))
model_cls_fruit = YOLO(os.path.join(BASE_DIR, 'run10_cls_best_fruit.pt'))
UPLOAD_FOLDER = os.path.join(BASE_DIR, 'uploads')
PROCESSED_FOLDER = os.path.join(BASE_DIR, 'processed')
os.makedirs(UPLOAD_FOLDER, exist_ok=True)
os.makedirs(PROCESSED_FOLDER, exist_ok=True)

# A dictionary to store detected classes for each request
detection_results = {}

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/test', methods=['GET'])
def test_route():
    return jsonify({'message': BASE_DIR}), 200

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
        unique_id = str(uuid.uuid4())
        unique_filename = unique_id + os.path.splitext(file.filename)[1]
        filepath = os.path.join(UPLOAD_FOLDER, unique_filename)
        file.save(filepath)
        processed_image_path = process_image(filepath, unique_id)
        print({'image_url': f'/processed/{os.path.basename(processed_image_path)}'})
        return jsonify({'image_url': f'/processed/{os.path.basename(processed_image_path)}', 'id': unique_id})

@app.route('/delete_all', methods=['POST'])
def delete_all_files():
    folder = request.json.get('folder')
    if not folder:
        return jsonify({'error': 'No folder specified'}), 400

    if folder == 'uploads':
        folder_path = UPLOAD_FOLDER
    elif folder == 'processed':
        folder_path = PROCESSED_FOLDER
    else:
        return jsonify({'error': 'Invalid folder specified'}), 400

    for filename in os.listdir(folder_path):
        file_path = os.path.join(folder_path, filename)
        try:
            if os.path.isfile(file_path) or os.path.islink(file_path):
                os.unlink(file_path)
            elif os.path.isdir(file_path):
                shutil.rmtree(file_path)
        except Exception as e:
            return jsonify({'error': f'Failed to delete {file_path}. Reason: {e}'}), 500

    return jsonify({'message': 'All files deleted successfully'}), 200

@app.route('/detections/<unique_id>', methods=['GET'])
def get_detections(unique_id):
    if unique_id not in detection_results:
        return jsonify({'error': 'No detections found for this ID'}), 404
    print(f'Detected items to send for {unique_id}: {detection_results[unique_id]}')  # Debug output
    return jsonify(detection_results[unique_id])

@app.route('/processed/<filename>')
def serve_processed_file(filename):
    return send_from_directory(PROCESSED_FOLDER, filename)

def process_image(image_path, unique_id):
    global detection_results
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
            elif label == 'Fruit':
                cls_result = model_cls_fruit.predict(source=detected_region)
                for r in cls_result:
                    label = cls_names_fruit[r.probs.top1]

            detected_class.add(label)
            print(f'Detected: {label}')  # Debug output for detected labels

            # Draw the bounding box and label on the image
            cv2.rectangle(img, (x1, y1), (x2, y2), (0,255,0), 2)
            cv2.putText(img, label, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0,255,0), 2)

    print(f'Final detected_class set: {detected_class}')  # Debug output for final detected set

    # Save processed image with a unique filename
    unique_processed_filename = str(uuid.uuid4()) + os.path.splitext(image_path)[1]
    processed_image_path = os.path.join(PROCESSED_FOLDER, unique_processed_filename)
    cv2.imwrite(processed_image_path, img)

    # Store detected classes with the unique identifier
    detection_results[unique_id] = list(detected_class)

    return processed_image_path

