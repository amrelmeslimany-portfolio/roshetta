import speech_recognition as sr
import pandas as pd

r = sr.Recognizer()

# Load drug dataset
drugs = pd.read_csv('drugs.csv')

with sr.Microphone(sample_rate=48000) as source:
    r.adjust_for_ambient_noise(source)
    r.energy_threshold = 400
    print("Say a drug name!")
    audio = r.listen(source, phrase_time_limit=2)
    
try:
    drug_name = r.recognize_google(audio)
    print("You said: " + drug_name)
    
    # Filter drug dataset by recognized drug name
    drug = drugs[drugs['drug_name'].str.lower() == drug_name.lower()].iloc[0]
    drug_name = drug['drug_name']
    if len(drug) > 0:
        drug_name = drug.iloc[0]['drug_name']
        print("Drug Name: " + drug_name)
    else:
        print("Sorry, drug not found.")
    
except sr.UnknownValueError:
    print("Sorry, I could not understand what you said.")
except sr.RequestError:
    print("Sorry, my speech service is currently down.")
except IndexError:
    print("Sorry, drug not found.")
