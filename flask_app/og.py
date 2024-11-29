from flask import Flask, request, jsonify, render_template, send_from_directory, session
import requests
from flask_cors import CORS
from flask_bcrypt import Bcrypt
import os
from ultralytics import YOLO
import shutil
import cv2
import uuid
import json
import logging
import textdistance

app = Flask(__name__)
bcrypt = Bcrypt(app)
CORS(app, supports_credentials=True)
logging.basicConfig(
    filename='server.log',
    level=logging.INFO,
    format='%(asctime)s %(levelname)s %(name)s %(threadName)s : %(message)s',
)
SPOONACULAR_API_KEY = '2e41df6958f64893849959955c867ddd'
EDAMAM_APP_ID = 'ecfb4cab'
EDAMAM_API_KEY = '11338adb5fc5b3271035fc0de60460ce'
# Full list of potential allergens
# Load food names from food.txt
def load_food_list(filepath):
    with open(filepath, 'r') as file:
        food_list = [line.strip().lower() for line in file.readlines()]
    return food_list

allergen_list = [
    "Dairy Free", "Gluten Free", "Wheat Free", "Egg Free", "Milk Free",
    "Peanut Free", "Tree Nut Free", "Soy Free", "Fish Free", "Shellfish Free",
    "Pork Free", "Red Meat Free", "Crustacean Free", "Celery Free", "Mustard Free",
    "Sesame Free", "Lupine Free", "Mollusk Free", "Alcohol Free", "Sulphite Free"
]

# Define the base directory
BASE_DIR = os.path.dirname(os.path.abspath(__file__))


food_names = load_food_list(os.path.join(BASE_DIR, 'food.txt'))

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

USER_DATA_FILE = os.path.join(BASE_DIR, 'users.json')
SECRET_KEY = 'supersecretkey'

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['MAX_CONTENT_LENGTH'] = 5 * 1024 * 1024 * 1024  # 5 GB limit
app.secret_key = SECRET_KEY



def load_users():
    if os.path.exists(USER_DATA_FILE):
        try:
            with open(USER_DATA_FILE, 'r') as file:
                file_content = file.read().strip()  # Read and strip whitespace
                if file_content:  # Check if the file has content
                    users = json.loads(file_content)
                else:
                    users = {}  # Return an empty dictionary if the file is empty
            return users
        except (OSError, IOError, json.JSONDecodeError) as e:
            logging.error(f"Error reading or parsing {USER_DATA_FILE}: {e}")
            return {}
    else:
        return {}

def save_users(users):
    try:
        with open(USER_DATA_FILE, 'w') as file:
            json.dump(users, file)
    except (OSError, IOError) as e:
        logging.error(f"Error writing to {USER_DATA_FILE}: {e}")

users = load_users()

@app.route('/')
def index():
    # List all image files from the 'static/images/PR_banner' folder
    image_folder = os.path.join(app.static_folder, 'images', 'PR_banner')
    image_filenames = [f for f in os.listdir(image_folder) if f.endswith(('png', 'jpg', 'jpeg', 'gif'))]

    return render_template('index.html', images=image_filenames)

@app.route('/api/edit_password', methods=['POST'])
def edit_password():
    data = request.json
    username = data.get('username')
    old_password = data.get('old_password')
    new_password = data.get('new_password')

    if not username or not old_password or not new_password:
        return jsonify({'error': 'Missing username, old password, or new password'}), 400

    if username in users:
        # Check if the old password is correct
        if bcrypt.check_password_hash(users[username]['password'], old_password):
            # Update the password
            hashed_new_password = bcrypt.generate_password_hash(new_password).decode('utf-8')
            users[username]['password'] = hashed_new_password
            save_users(users)
            return jsonify({
                'message': 'Password updated successfully',
                'username': username,
                'email': users[username]['email'],
                'allergies': users[username]['allergies']
            }), 200
        else:
            return jsonify({'error': 'Old password is incorrect'}), 400
    else:
        return jsonify({'error': 'User not found'}), 404

@app.route('/api/register', methods=['POST'])
def api_register():
    data = request.json
    username = data.get('username')
    password = data.get('password')
    email = data.get('email')
    allergies = data.get('allergies', [])

    if not username or not password or not email:
        return jsonify({'error': 'Missing username, password, or email'}), 400

    if username in users:
        return jsonify({'error': 'Username already exists'}), 400

    hashed_password = bcrypt.generate_password_hash(password).decode('utf-8')
    users[username] = {
        'password': hashed_password,
        'email': email,
        'allergies': allergies,
        'suspended': False
    }
    save_users(users)

    user_path = os.path.join(app.config['UPLOAD_FOLDER'], username)
    if not os.path.exists(user_path):
        try:
            os.makedirs(user_path)
        except (OSError, IOError) as e:
            logging.error(f"Error creating directory {user_path}: {e}")
            return jsonify({'error': 'Internal server error'}), 500

    logging.info(f"New user registered: {username}")
    return jsonify({'message': 'User registered successfully'}), 200

@app.route('/api/login', methods=['POST'])
def api_login():
    data = request.json
    username = data.get('username')
    password = data.get('password')

    if not username or not password:
        return jsonify({'error': 'Missing username or password'}), 400

    if username in users and bcrypt.check_password_hash(users[username]['password'], password):
        if users[username].get('suspended'):
            return jsonify({'error': 'Account is suspended!'}), 403

        session['logged_in'] = True
        session['username'] = username
        session['email'] = users[username]['email']
        session['allergies'] = users[username]['allergies']
        logging.info(f"User {username} logged in.")

        return jsonify({'message': 'User logged in', 'email': session['email'], 'allergies': session['allergies']}), 200
    else:
        logging.warning(f"Failed login attempt for username: {username}")
        return jsonify({'error': 'Invalid username or password!'}), 400

@app.route('/api/update_user', methods=['POST'])
def update_user():
    data = request.json
    username = data.get('username')
    email = data.get('email')
    allergies = data.get('allergies')

    if not username:
        return jsonify({'error': 'Missing username'}), 400

    if username in users:
        if email:
            users[username]['email'] = email
        if allergies:
            try:
                users[username]['allergies'] = allergies
            except json.JSONDecodeError:
                return jsonify({'error': 'Invalid allergies data'}), 400

        save_users(users)
        return jsonify({
            'message': f'User {username} updated successfully',
            'username': username,
            'email': users[username]['email'],
            'allergies': users[username]['allergies']
        }), 200
    else:
        return jsonify({'error': f'User {username} not found'}), 404

@app.route('/api/logout', methods=['POST'])
def api_logout():
    session.clear()
    return jsonify({'message': 'User logged out successfully'}), 200


@app.route('/process_feedback', methods=['POST'])
def process_feedback():
    feedback = request.get_json()

    if feedback is None:
        return jsonify({'message': 'Invalid data'}), 400

    # Load existing feedback data
    try:
        with open(os.path.join(BASE_DIR, 'feedback.json'), 'r') as file:
            feedback_data = json.load(file)
    except FileNotFoundError:
        feedback_data = []

    # Append new feedback
    feedback_data.append(feedback)
    print(feedback_data)

    # Save updated feedback data
    with open(os.path.join(BASE_DIR, 'feedback.json'), 'w') as file:
        json.dump(feedback_data, file, indent=4)

    return jsonify({'message': 'Feedback submitted successfully'}), 200

@app.route('/test', methods=['GET'])
def test_route():
    return jsonify({'message': BASE_DIR}), 200

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        print('No file part')
        return jsonify({'error': 'No file part'}), 400
    file = request.files['file']
    if file.filename == '':
        print('No selected file')
        return jsonify({'error': 'No selected file'}), 400
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



def autocorrect_food_name(query):
    """Auto-corrects the food name based on similarity to known food names."""
    if not query:
        return None
    closest_match = min(food_names, key=lambda name: textdistance.levenshtein(query.lower(), name))
    if textdistance.levenshtein(query.lower(), closest_match) <= 2:  # Allowing a small threshold for correction
        return closest_match
    return query  # Return the original query if no close match is found

@app.route('/search_allergens', methods=['GET'])
def search_allergens():
    food_name = request.args.get('food')

    if not food_name:
        return jsonify({"error": "Food name is required"}), 400

    # Apply autocorrect to the food name
    corrected_food_name = autocorrect_food_name(food_name)

    # Use the Edamam API to get allergen information
    edamam_url = f"https://api.edamam.com/api/nutrition-data?app_id={EDAMAM_APP_ID}&app_key={EDAMAM_API_KEY}&ingr={corrected_food_name}"
    edamam_response = requests.get(edamam_url)

    if edamam_response.status_code != 200:
        return jsonify({"error": "Failed to fetch data from Edamam"}), edamam_response.status_code

    edamam_data = edamam_response.json()

    # Extract health labels which include potential allergens
    health_labels = edamam_data.get('healthLabels', [])

    # Convert health labels to a more readable format (e.g., "Dairy Free" -> "Dairy Free")
    formatted_health_labels = {label.replace('_', ' ').title() for label in health_labels}

    # Determine which allergens might be present in the food (i.e., not in the formatted_health_labels set)
    present_allergens = [allergen.replace(' Free', '') for allergen in allergen_list if allergen not in formatted_health_labels]

    # If no specific allergens are found, provide a fallback message
    if not present_allergens:
        present_allergens = ["No specific allergens detected"]

    # Return the list of present allergens to the frontend
    return jsonify({
        "name": corrected_food_name,
        "allergens": present_allergens
    }), 200



if __name__ == '__main__':
    app.run(debug=True)
