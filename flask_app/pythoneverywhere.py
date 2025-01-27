from flask import Flask, request, jsonify, render_template, send_from_directory, session
import requests
from flask_cors import CORS
from flask_bcrypt import Bcrypt
import os
import shutil
import cv2
import uuid
import json
import logging
import textdistance
import google.generativeai as genai
from google.ai.generativelanguage_v1beta.types import content

app = Flask(__name__)
bcrypt = Bcrypt(app)
CORS(app, supports_credentials=True)
logging.basicConfig(
    filename='server.log',
    level=logging.INFO,
    format='%(asctime)s %(levelname)s %(name)s %(threadName)s : %(message)s',
)
genai.configure(api_key='AIzaSyCD9TjnWBBVehg6BRoFzruk_CPbQCBP7oE')
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
    def upload_to_gemini(path, mime_type=None):
        """Uploads the given file to Gemini."""
        file = genai.upload_file(path, mime_type=mime_type)
        print(f"Uploaded file '{file.display_name}' as: {file.uri}")
        return file
    def delete_file_from_gemini(file_uri):
        """Deletes the given file from Gemini by extracting the file ID."""
        try:
            # Extract the file ID from the URI (everything after 'files/')
            file_id = file_uri.split('/files/')[-1]

            # Ensure the file ID is within the required length
            if len(file_id) > 40:
                raise ValueError(f"File ID is too long: {file_id}")

            # Delete the file using the file ID
            genai.delete_file(name=f"files/{file_id}")
            print(f"Deleted file from Gemini: {file_uri}")
        except Exception as e:
            print(f"Failed to delete file: {e}")

    # Determine mime type based on file extension
    ext = os.path.splitext(image_path)[1].lower()
    if ext == '.jpg' or ext == '.jpeg':
        mime_type = 'image/jpeg'
    elif ext == '.png':
        mime_type = 'image/png'
    else:
        mime_type = 'application/octet-stream'

    uploaded_file = upload_to_gemini(image_path, mime_type=mime_type)

        # Create the model
    generation_config = {
    "temperature": 0.95,
    "top_p": 0.95,
    "top_k": 64,
    "max_output_tokens": 8192,
    "response_schema": content.Schema(
        type = content.Type.OBJECT,
        enum = [],
        required = ["food_component", "Allergy group"],
        properties = {
        "debug": content.Schema(
            type = content.Type.STRING,
        ),
        "food_component": content.Schema(
            type = content.Type.ARRAY,
            items = content.Schema(
            type = content.Type.OBJECT,
            enum = [],
            required = ["component_name", "position"],
            properties = {
                "component_name": content.Schema(
                type = content.Type.STRING,
                ),
                "position": content.Schema(
                type = content.Type.OBJECT,
                enum = [],
                required = ["x", "y"],
                properties = {
                    "x": content.Schema(
                    type = content.Type.NUMBER,
                    ),
                    "y": content.Schema(
                    type = content.Type.NUMBER,
                    ),
                },
                ),
            },
            ),
        ),
        "Allergy group": content.Schema(
            type = content.Type.OBJECT,
            enum = [],
            required = ["Soy", "Cow_milk", "Wheat", "Egg", "Fish", "Seafood", "Peanut", "Shelled_nut"],
            properties = {
            "Soy": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            "Cow_milk": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            "Wheat": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            "Egg": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            "Fish": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            "Seafood": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            "Peanut": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            "Shelled_nut": content.Schema(
                type = content.Type.BOOLEAN,
            ),
            },
        ),
        },
    ),
    "response_mime_type": "application/json",
    }

    model = genai.GenerativeModel(
    model_name="gemini-exp-1121",
    generation_config=generation_config,
    )


    # TODO Make these files available on the local file system
    # You may need to update the file paths
    files = [
    upload_to_gemini(os.path.join(BASE_DIR, "x7jPxj9YtJfv97hnC3mMmQog5VwuYojZ7tlrhczGXIV.png"), mime_type="image/png"),
    upload_to_gemini(os.path.join(BASE_DIR, 'neat_emmapharaoh_19march24_12.jpg'), mime_type="image/jpeg"),
    upload_to_gemini(os.path.join(BASE_DIR, 'One-pan-spaghetti-f2aca14.jpg'), mime_type="image/jpeg"),
    ]

    response = model.generate_content([
    "You are a component of a food allergy detection API. Your primary task is to analyze the provided image and identify all food components present. This includes detailed detection of the main items (e.g., pizza, hotdog, shrimp, sausage, beef) and their sub-components. For example, if you detect a burger, you must separately list its components, such as beef, cheese, and lettuce. Similarly, if the image contains pizza, you should not just return \"pizza\" but also specify the sub-components like crust, sauce, cheese, and toppings. Be meticulous in identifying edible elements, including those that might be found in liquid foods, such as soup. Avoid identifying containers; focus solely on the edible items. Use common names for components (e.g., \"beef\" instead of \"vegan beef\") if the specific type cannot be determined.For each detected food component, output the following:Component Name: The name of the food item.Position: The normalized center coordinates (x, y) of the component within the image. Ensure these coordinates follow the YOLO-style format, including only the center position. Provide as many decimal places as possible for better accuracy, even if the exact position needs to be approximated. Avoid overlapping component positions.Additionally, you must determine whether specific allergen groups are present in the image. The allergen groups to identify are: Soy, Cow Milk, Wheat, Egg, Fish, Seafood, Peanut, and Shelled Nut. For each allergen group, return true if it is present and false if it is not.Ensure all coordinates (x, y) are normalized between 0 and 1. Include a \"debug\" field in the output, which can contain relevant debugging information or be left empty. All required fields must be included in the output, even if some values are false or empty.Finally, ensure the output follows this strict format: {  \"type\": \"object\",  \"properties\": {    \"debug\": {      \"type\": \"string\"    },    \"food_component\": {      \"type\": \"array\",      \"items\": {        \"type\": \"object\",        \"properties\": {          \"component_name\": {            \"type\": \"string\"          },          \"position\": {            \"type\": \"object\",            \"properties\": {              \"x\": {                \"type\": \"number\"              },              \"y\": {                \"type\": \"number\"              }            },            \"required\": [              \"x\",              \"y\"            ]          }        },        \"required\": [          \"component_name\",          \"position\"        ]      }    },    \"Allergy group\": {      \"type\": \"object\",      \"properties\": {        \"Soy\": {          \"type\": \"boolean\"        },        \"Cow_milk\": {          \"type\": \"boolean\"        },        \"Wheat\": {          \"type\": \"boolean\"        },        \"Egg\": {          \"type\": \"boolean\"        },        \"Fish\": {          \"type\": \"boolean\"        },        \"Seafood\": {          \"type\": \"boolean\"        },        \"Peanut\": {          \"type\": \"boolean\"        },        \"Shelled_nut\": {          \"type\": \"boolean\"        }      },      \"required\": [        \"Soy\",        \"Cow_milk\",        \"Wheat\",        \"Egg\",        \"Fish\",        \"Seafood\",        \"Peanut\",        \"Shelled_nut\"      ]    }  },  \"required\": [    \"food_component\",    \"Allergy group\"  ]} Remember to list all components and sub-components as comprehensively and accurately as possible while adhering to this format.",
    "input: ",
    files[0],
    "\"food_component\": [{\"component_name\": \"pizza\", \"position\": {\"x\": 0.25, \"y\": 0.55}}, {\"component_name\": \"pizza slice\", \"position\": {\"x\": 0.19, \"y\": 0.64}}, {\"component_name\": \"pizza slice\", \"position\": {\"x\": 0.28, \"y\": 0.81}}, {\"component_name\": \"pizza topping\", \"position\": {\"x\": 0.19, \"y\": 0.74}}, {\"component_name\": \"red onion\", \"position\": {\"x\": 0.23, \"y\": 0.72}}, {\"component_name\": \"cheese\", \"position\": {\"x\": 0.18, \"y\": 0.69}}, {\"component_name\": \"hotdog\", \"position\": {\"x\": 0.65, \"y\": 0.36}}, {\"component_name\": \"hotdog bun\", \"position\": {\"x\": 0.69, \"y\": 0.28}}, {\"component_name\": \"sausage\", \"position\": {\"x\": 0.56, \"y\": 0.43}}, {\"component_name\": \"hamburger\", \"position\": {\"x\": 0.85, \"y\": 0.42}}, {\"component_name\": \"hamburger bun\", \"position\": {\"x\": 0.78, \"y\": 0.32}}, {\"component_name\": \"beef patty\", \"position\": {\"x\": 0.85, \"y\": 0.55}}, {\"component_name\": \"lettuce\", \"position\": {\"x\": 0.82, \"y\": 0.47}}, {\"component_name\": \"cheese\", \"position\": {\"x\": 0.82, \"y\": 0.43}}, {\"component_name\": \"onion rings\", \"position\": {\"x\": 0.47, \"y\": 0.62}}, {\"component_name\": \"French fries\", \"position\": {\"x\": 0.62, \"y\": 0.69}}, {\"component_name\": \"potato chips\", \"position\": {\"x\": 0.43, \"y\": 0.30}}, {\"component_name\": \"tortilla chips\", \"position\": {\"x\": 0.63, \"y\": 0.15}}, {\"component_name\": \"potato chips\", \"position\": {\"x\": 0.30, \"y\": 0.32}}, {\"component_name\": \"peanuts\", \"position\": {\"x\": 0.21, \"y\": 0.28}}, {\"component_name\": \"popcorn\", \"position\": {\"x\": 0.21, \"y\": 0.11}}, {\"component_name\": \"ketchup\", \"position\": {\"x\": 0.70, \"y\": 0.84}},{\"component_name\":\"cola\",\"position\":{\"x\":0.93,\"y\":0.15}}],  \"Allergy group\": {\"Soy\": false, \"Cow_milk\": true, \"Wheat\": true, \"Egg\": true, \"Fish\": false, \"Seafood\": false, \"Peanut\": true, \"Shelled_nut\": false}, \"debug\": \"\"}",
    "input: ",
    files[1],
    "{\"food_component\": [{\"component_name\": \"sandwich\", \"position\": {\"x\": 0.11, \"y\": 0.28}}, {\"component_name\": \"sandwich bread\", \"position\": {\"x\": 0.09, \"y\": 0.39}}, {\"component_name\": \"filling\", \"position\": {\"x\": 0.14, \"y\": 0.22}}, {\"component_name\": \"salad\", \"position\": {\"x\": 0.34, \"y\": 0.44}}, {\"component_name\": \"lettuce\", \"position\": {\"x\": 0.31, \"y\": 0.54}}, {\"component_name\": \"tomatoes\", \"position\": {\"x\": 0.26, \"y\": 0.38}}, {\"component_name\": \"cucumber\", \"position\": {\"x\": 0.36, \"y\": 0.49}}, {\"component_name\": \"peppers\", \"position\": {\"x\": 0.30, \"y\": 0.34}}, {\"component_name\": \"red cabbage\", \"position\": {\"x\": 0.39, \"y\": 0.51}}, {\"component_name\": \"chickpeas\", \"position\": {\"x\": 0.34, \"y\": 0.37}}, {\"component_name\": \"falafel\", \"position\": {\"x\": 0.42, \"y\": 0.35}}, {\"component_name\": \"falafel balls\", \"position\": {\"x\": 0.47, \"y\": 0.41}}, {\"component_name\": \"sauce\", \"position\": {\"x\": 0.41, \"y\": 0.49}}, {\"component_name\": \"hamburger\", \"position\": {\"x\": 0.64, \"y\": 0.32}}, {\"component_name\": \"hamburger bun\", \"position\": {\"x\": 0.64, \"y\": 0.25}}, {\"component_name\": \"patty\", \"position\": {\"x\": 0.63, \"y\": 0.35}}, {\"component_name\": \"lettuce\", \"position\": {\"x\": 0.65, \"y\": 0.37}}, {\"component_name\": \"tomato\", \"position\": {\"x\": 0.64, \"y\": 0.31}}, {\"component_name\": \"salad\", \"position\": {\"x\": 0.86, \"y\": 0.44}}, {\"component_name\": \"lettuce\", \"position\": {\"x\": 0.86, \"y\": 0.46}}, {\"component_name\": \"chicken\", \"position\": {\"x\": 0.89, \"y\": 0.48}}, {\"component_name\": \"tomatoes\", \"position\": {\"x\": 0.83, \"y\": 0.36}}, {\"component_name\": \"cheese\", \"position\": {\"x\": 0.90, \"y\": 0.38}}, {\"component_name\": \"tater tots\", \"position\": {\"x\": 0.10, \"y\": 0.73}}, {\"component_name\": \"hot dog\", \"position\": {\"x\": 0.48, \"y\": 0.76}}, {\"component_name\": \"hot dog bun\", \"position\": {\"x\": 0.51, \"y\": 0.66}}, {\"component_name\": \"sausage\", \"position\": {\"x\": 0.44, \"y\": 0.84}}, {\"component_name\": \"sauce\", \"position\": {\"x\": 0.47, \"y\": 0.79}}, {\"component_name\": \"onions\", \"position\": {\"x\": 0.52, \"y\": 0.70}}, {\"component_name\": \"French fries\", \"position\": {\"x\": 0.64, \"y\": 0.61}}, {\"component_name\": \"sauce\", \"position\": {\"x\": 0.09, \"y\": 0.05}}, {\"component_name\": \"sauce\", \"position\": {\"x\": 0.91, \"y\": 0.89}}], \"Allergy group\": {\"Soy\": false, \"Cow_milk\": true, \"Wheat\": true, \"Egg\": false, \"Fish\": false, \"Seafood\": false, \"Peanut\": false, \"Shelled_nut\": true}, \"debug\": \"\"}",
    "input: ",
    files[2],
    "{\"food_component\": [{\"component_name\": \"spaghetti\", \"position\": {\"x\": 0.25, \"y\": 0.4}}, {\"component_name\": \"meatballs\", \"position\": {\"x\": 0.26, \"y\": 0.5}}, {\"component_name\": \"tomato sauce\", \"position\": {\"x\": 0.25, \"y\": 0.5}}, {\"component_name\": \"basil\", \"position\": {\"x\": 0.395, \"y\": 0.25}}, {\"component_name\": \"spaghetti\", \"position\": {\"x\": 0.85, \"y\": 0.55}}, {\"component_name\": \"meatballs\", \"position\": {\"x\": 0.95, \"y\": 0.62}}, {\"component_name\": \"tomato sauce\", \"position\": {\"x\": 0.85, \"y\": 0.65}}, {\"component_name\": \"basil\", \"position\": {\"x\": 0.88, \"y\": 0.71}}], \"Allergy group\": {\"Soy\": false, \"Cow_milk\": false, \"Wheat\": true, \"Egg\": true, \"Fish\": false, \"Seafood\": false, \"Peanut\": false, \"Shelled_nut\": false}, \"debug\": \"\"}",
    "input: ",
    uploaded_file,
    " ",
    ])
    response_text = response.text
    try:
        detection_result = json.loads(response_text)
    except json.JSONDecodeError as e:
        print(f"Failed to parse response: {e}")
        detection_result = {}


    img = cv2.imread(image_path)
    height, width, _ = img.shape

    for component in detection_result.get("food_component", []):
        name = component["component_name"]
        x_norm = component["position"]["x"]
        y_norm = component["position"]["y"]

        # Denormalize coordinates
        x = int(x_norm * width)
        y = int(y_norm * height)

        # Draw a circle at the position
        cv2.circle(img, (x, y), radius=5, color=(0, 0, 255), thickness=-1)

        # Put the component name near the circle
        cv2.putText(img, name, (x + 10, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

    # Save the annotated image
    unique_processed_filename = str(uuid.uuid4()) + os.path.splitext(image_path)[1]
    processed_image_path = os.path.join(PROCESSED_FOLDER, unique_processed_filename)
    cv2.imwrite(processed_image_path, img)

    # Update detection_results
    detection_results[unique_id] = detection_result
    delete_file_from_gemini(uploaded_file.uri)
    for file in files:
        delete_file_from_gemini(file.uri)
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
    import google.generativeai as genai
    from google.ai.generativelanguage_v1beta.types import content

    food_name = request.args.get('food')
    if not food_name:
        return jsonify({"error": "Food name is required"}), 400


    # Define the JSON schema for your new Gemini call
    generation_config = {
        "temperature": 1,
        "top_p": 0.95,
        "top_k": 40,
        "max_output_tokens": 8192,
        "response_schema": content.Schema(
            type=content.Type.OBJECT,
            required=["food_component", "Allergy_group", "corrected name"],
            properties={
                "debug": content.Schema(
                    type=content.Type.STRING,
                ),
                "food_component": content.Schema(
                    type=content.Type.ARRAY,
                    items=content.Schema(
                        type=content.Type.OBJECT,
                        required=["component_name"],
                        properties={
                            "component_name": content.Schema(
                                type=content.Type.STRING,
                            ),
                        },
                    ),
                ),
                "Allergy_group": content.Schema(
                    type=content.Type.OBJECT,
                    required=[
                        "Soy",
                        "Cow_milk",
                        "Wheat",
                        "Egg",
                        "Fish",
                        "Seafood",
                        "Peanut",
                        "Shelled_nut",
                    ],
                    properties={
                        "Soy": content.Schema(type=content.Type.BOOLEAN),
                        "Cow_milk": content.Schema(type=content.Type.BOOLEAN),
                        "Wheat": content.Schema(type=content.Type.BOOLEAN),
                        "Egg": content.Schema(type=content.Type.BOOLEAN),
                        "Fish": content.Schema(type=content.Type.BOOLEAN),
                        "Seafood": content.Schema(type=content.Type.BOOLEAN),
                        "Peanut": content.Schema(type=content.Type.BOOLEAN),
                        "Shelled_nut": content.Schema(type=content.Type.BOOLEAN),
                    },
                ),
                "corrected name": content.Schema(
                    type=content.Type.STRING,
                ),
            },
        ),
        "response_mime_type": "application/json",
    }

    # Create the model and attach system instructions:
    model = genai.GenerativeModel(
        model_name="gemini-1.5-flash",  # or whichever model is appropriate
        generation_config=generation_config,
        system_instruction=(
            "You are a component of a food allergy detection API. "
            "Given a user-provided food name, you will:\n"
            "1) Autocorrect the name if necessary.\n"
            "2) Provide a list of possible food components (as an array of objects with 'component_name').\n"
            "3) Mark booleans in 'Allergy_group' for each of these allergens: Soy, Cow_milk, Wheat, Egg, Fish, "
            "Seafood, Peanut, Shelled_nut.\n"
            "4) Include the corrected name in the field 'corrected name'.\n"
            "5) Optionally fill 'debug' with any extra info, or leave it empty.\n"
            "Respond in strict JSON matching the given schema."
        ),
    )

    # Start a "chat" or "generation" session
    chat_session = model.start_chat(history=[])
    # Send the user’s input (the user typed "food_name")
    response = chat_session.send_message(food_name)

    # The .text from the model is expected to be valid JSON with the keys:
    # "food_component", "Allergy_group", and "corrected name"
    try:
        gemini_json = json.loads(response.text)
    except json.JSONDecodeError as e:
        return jsonify({"error": "Gemini returned invalid JSON", "raw": response.text}), 500

    # Return the Gemini JSON directly to the frontend
    return jsonify(gemini_json), 200



if __name__ == '__main__':
    app.run(debug=True)
