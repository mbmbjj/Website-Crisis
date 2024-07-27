
from flask import Flask, request, jsonify, render_template
import os
from ultralytics import YOLO
import shutil
import cv2
#import model
app = Flask(__name__)
#load model here
model = YOLO('run14_best.pt')
model_cls = YOLO('run12_cls_best.pt')
UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'})
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'})
    if file:
        filepath = os.path.join(UPLOAD_FOLDER, file.filename)
        file.save(filepath)
        detections = process_image(filepath)
        return jsonify(list(detections))

def process_image(image_path):
    
    subfolder_path = os.path.join(UPLOAD_FOLDER, 'detect')
    if os.path.exists(subfolder_path):
        shutil.rmtree(subfolder_path)
    os.makedirs(subfolder_path, exist_ok=True)
    # add model here
    results = model(image_path)
    
    save_dir = 'uploads/detect/'
    os.makedirs(save_dir, exist_ok=True)
    names = model.names
    cls_names = model_cls.names
    # Read the image
    img = cv2.imread(image_path)
    
    # Iterate through results (in case of batch processing, this handles each image's results)
    detected_class = set()
    for result in results:
        # Counter for naming detected regions
        count = 0
        
        # Iterate through each detected box
        for box in result.boxes:
            if(names[int(box.cls)]!='Vegetable'):
                detected_class.add(names[int(box.cls)])
            else:
                # Extract bounding box coordinates
                x1, y1, x2, y2 = map(int, box.xyxy[0])
                # Crop the detected region from the image
                detected_region = img[y1:y2, x1:x2]
                
                #detected_save_path = os.path.join(save_dir, f'detected_{x1}_{y1}.png')
                #cv2.imwrite(detected_save_path, detected_region)
                cls_result = model_cls.predict(source=detected_region)
                for r in cls_result:
                    print(r.probs.top1)
                    detected_class.add(cls_names[r.probs.top1])
            count += 1
    
    
    return detected_class

if __name__ == '__main__':
    app.run(debug=True)

