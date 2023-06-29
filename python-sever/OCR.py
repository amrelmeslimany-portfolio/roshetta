import pytesseract
import cv2

pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'


def process_image(image_path):
    img = cv2.imread(image_path)

    # Preprocessing
    img_gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    img_gray = cv2.resize(img_gray, None, fx=2, fy=2,
                          interpolation=cv2.INTER_CUBIC)
    img_gray = cv2.GaussianBlur(img_gray, (5, 5), 0)
    img_threshold = cv2.threshold(
        img_gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)[1]

    # Apply OCR
    custom_config = r'--oem 3 --psm 6'
    text = pytesseract.image_to_string(img_threshold, config=custom_config)

    # Post-processing
    # text = text.replace('\n', ' ')
    text = text.strip()

    return text
