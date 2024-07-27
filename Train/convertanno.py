# import cv2
# import numpy as np
# import os

# def convert_mask_to_yolo_format(mask_path, output_path, class_thresholds):
#     mask = cv2.imread(mask_path, cv2.IMREAD_GRAYSCALE)
#     height, width = mask.shape
#     annotations = []

#     for class_id in class_thresholds:
#         # Create a binary mask for the current class
#         output_class=0
#         if class_id == 0:
#              continue
#         elif class_id<25:
#             output_class=class_id
#         elif class_id<=45:
#             output_class=25
#         elif class_id<=51:
#             if class_id==49: 
#                 output_class=27
#             else :
#                 output_class=26
#         elif class_id<69:
#             output_class=class_id-24
#         elif class_id<=96:
#             output_class = 45
#         elif class_id<=101:
#             output_class = 46
#         else: 
#             output_class=class_id-55
            
#         binary_mask = (mask == class_id).astype(np.uint8) * 255
#         contours, _ = cv2.findContours(binary_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        
#         for contour in contours:
#             x, y, w, h = cv2.boundingRect(contour)
#             x_center = (x + w / 2) / width
#             y_center = (y + h / 2) / height
#             w_normalized = w / width
#             h_normalized = h / height
#             annotations.append(f"{output_class} {x_center} {y_center} {w_normalized} {h_normalized}")
    
#     with open(output_path, 'w') as f:
#         f.write('\n'.join(annotations))

# def process_all_images(mask_folder, output_folder, class_thresholds):
#     if not os.path.exists(output_folder):
#         os.makedirs(output_folder)

#     for mask_filename in os.listdir(mask_folder):
#         if mask_filename.endswith('.png'):
#             mask_path = os.path.join(mask_folder, mask_filename)
#             output_path = os.path.join(output_folder, mask_filename.replace('.png', '.txt'))
#             convert_mask_to_yolo_format(mask_path, output_path, class_thresholds)

# # Define the folder paths
# mask_folder = 'D:/Document/ml/ML/Food_dataset/Images/masks/train'
# output_folder = 'D:/Document/ml/ML/Food_dataset/Images/label/train'

# # Define the grayscale level thresholds for different classes (0 to 103)
# class_thresholds = list(range(104))

# # Process all images
# process_all_images(mask_folder, output_folder, class_thresholds)


#------------upper is object detection--------
import cv2
import numpy as np
import os

# Open the file in read mode
with open('D:/Document/ml/ML/Food_dataset/category_id.txt', 'r') as file:
    # Read all lines from the file
    lines = file.readlines()

# Function to remove the first word before the space
def remove_first_word(line):
    return ' '.join(line.split()[1:])

# Apply the function to each line
cat = [remove_first_word(line) for line in lines]
print(cat)

def convert_mask_to_yolo_format(mask_path, output_path, class_thresholds, img_folder, cropped_folder):
    mask = cv2.imread(mask_path, cv2.IMREAD_GRAYSCALE)
    height, width = mask.shape
    annotations = []

    for class_id in class_thresholds:
       
        binary_mask = (mask == class_id).astype(np.uint8) * 255
        contours, _ = cv2.findContours(binary_mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

        for contour in contours:
            x, y, w, h = cv2.boundingRect(contour)
            x_center = (x + w / 2) / width
            y_center = (y + h / 2) / height
            w_normalized = w / width
            h_normalized = h / height
            annotations.append(f"{class_id} {x_center} {y_center} {w_normalized} {h_normalized}")

            
            image_filename = os.path.basename(mask_path).replace('mask', 'image').replace('.png', '.jpg')  # Ensure correct filename
            image_path = os.path.join(img_folder, image_filename)
            image = cv2.imread(image_path)
                
            if image is None:
                print(f"Warning: Image at path '{image_path}' could not be read.")
                continue
                
            lh, lw, _ = image.shape

                # Ensure coordinates are within image boundaries
            x = max(0, int(x_center * lw - (w_normalized * lw) / 2))
            y = max(0, int(y_center * lh - (h_normalized * lh) / 2))
            w = min(lw - x, int(w_normalized * lw))
            h = min(lh - y, int(h_normalized * lh))

                # Check if the bounding box is valid
            if w <= 0 or h <= 0:
                print(f"Invalid bounding box: ({x}, {y}, {w}, {h}) for image '{image_path}'")
                continue

            boxedImage = image[y:y+h, x:x+w]

            if boxedImage.size == 0:
                print(f"Empty cropped image for bounding box: ({x}, {y}, {w}, {h}) in '{image_path}'")
                continue

            folder_index = class_id
            dynamic_folder_path = os.path.join(cropped_folder, cat[folder_index])
            if not os.path.exists(dynamic_folder_path):
                os.makedirs(dynamic_folder_path)
            output_image_path = os.path.join(dynamic_folder_path, f'{os.path.basename(mask_path).replace(".png", "")}_{class_id}.jpg')
            cv2.imwrite(output_image_path, boxedImage)

    with open(output_path, 'w') as f:
        f.write('\n'.join(annotations))

def process_all_images(mask_folder, output_folder, class_thresholds, img_folder, cropped_folder):
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    for mask_filename in os.listdir(mask_folder):
        if mask_filename.endswith('.png'):
            mask_path = os.path.join(mask_folder, mask_filename)
            output_path = os.path.join(output_folder, mask_filename.replace('.png', '.txt'))
            convert_mask_to_yolo_format(mask_path, output_path, class_thresholds, img_folder, cropped_folder)

# Define the folder paths
mask_folder = 'D:/Document/ml/ML/Food_dataset/Images/masks/train'
output_folder = 'D:/Document/ml/ML/Food_dataset/Images/label/train'
img_folder = 'D:/Document/ml/ML/Food_dataset/Images/images/train'
cropped_folder = 'D:/Document/ml/ML/Food_dataset/Images/cropped/train'

# Define the grayscale level thresholds for different classes (0 to 103)
class_thresholds = list(range(104))

# Process all images
process_all_images(mask_folder, output_folder, class_thresholds, img_folder, cropped_folder)
