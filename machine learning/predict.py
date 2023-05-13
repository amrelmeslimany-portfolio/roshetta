import pandas as pd
from imblearn.over_sampling import SMOTE
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split
from flask import Flask, request, render_template

app = Flask(__name__, template_folder='templates')

df_drug = pd.read_csv("drug200.csv")

bin_age = [0, 19, 29, 39, 49, 59, 69, 80]
category_age = ['<20s', '20s', '30s', '40s', '50s', '60s', '>60s']
df_drug['Age_binned'] = pd.cut(df_drug['Age'], bins=bin_age, labels=category_age)
df_drug = df_drug.drop(['Age'], axis=1)

bin_NatoK = [0, 9, 19, 29, 50]
category_NatoK = ['<10', '10-20', '20-30', '>30']
df_drug['Na_to_K_binned'] = pd.cut(df_drug['Na_to_K'], bins=bin_NatoK, labels=category_NatoK)
df_drug = df_drug.drop(['Na_to_K'], axis=1)

X = df_drug.drop(["Drug"], axis=1)
y = df_drug["Drug"]

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=43)

X_train = pd.get_dummies(X_train)
X_test = pd.get_dummies(X_test)

X_train, y_train = SMOTE().fit_resample(X_train, y_train)

LRclassifier = LogisticRegression(solver='liblinear', max_iter=5000)
LRclassifier.fit(X_train, y_train)

@app.route('/')
def home():
    return render_template('form.html')

@app.route('/predict', methods=['POST'])
def predict():
    age = int(request.form['age'])
    sex = request.form['sex']
    bp = request.form['bp']
    cholesterol = request.form['cholesterol']
    na_to_k = float(request.form['na_to_k'])

    # Create a new dataframe with the input data
    user_df = pd.DataFrame({'Age_binned': pd.cut([age], bins=bin_age, labels=category_age),
                            'Sex': [sex],
                            'BP': [bp],
                            'Cholesterol': [cholesterol],
                            'Na_to_K_binned': pd.cut([na_to_k], bins=bin_NatoK, labels=category_NatoK)})

    # Convert categorical variables to one-hot encoding
    user_df = pd.get_dummies(user_df)

    # Add missing columns to the user_df (if any)
    missing_cols = set(X_train.columns) - set(user_df.columns)
    for col in missing_cols:
        user_df[col] = 0

    # Reorder the columns to match the order in the X_train dataframe
    user_df = user_df[X_train.columns]

    # Make prediction using the model
    y_pred = LRclassifier.predict(user_df)

    return render_template('result.html', age=age, sex=sex, bp=bp, cholesterol=cholesterol, na_to_k=na_to_k, result=y_pred[0])

if __name__ == '__main__':
    app.run(debug=True)