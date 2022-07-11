import imp
from flask import Flask,request,jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

@app.route("/api/discountCalculator/<amount>",methods=['GET'])
def GetNewAmount(amount):
    amount = float(amount)
    if(amount >= 10000):
        amount*=0.88
    elif(amount>=5000):
        amount*=0.92
    elif(amount >= 3000):
        amount*=0.97;
    return jsonify({"TotalAmount": str(amount)})

if __name__ == "__main__":
    app.run(debug=True,
            host='localhost',
            port=8000)