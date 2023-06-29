from OCR import process_image
from flask import Flask, request, jsonify

app = Flask(__name__)
app.debug = True

@app.route('/extract_text', methods=['POST'])
def extract_text():
    try:
        if 'image' not in request.files:
            return jsonify({'status': 'error' ,
                            'message' : "يجب ارسال الصورة في المتغير image"})

        image = request.files['image']
        # Save the image to a file
        image.save('image.jpg')  
        # ML to Extract text
        extracted_text = process_image('image.jpg') 
        
        return jsonify({'status': "success" ,
                         'message': "تم استخراج النص بنجاح",
                         'data': extracted_text})
    except Exception as e:
        response = {'status' : "error" , 'message' : str(e)}
        return jsonify(response)


if __name__ == '__main__':
    app.run()
