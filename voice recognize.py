import pandas as pd
import speech_recognition as sr

drugs = pd.read_csv('drugs_side_effects_drugs_com.csv')

def get_drug_info(drug_name):
    drug = drugs[(drugs['drug_name'].str.lower() == drug_name.lower()) | (drugs['generic_name'].str.lower() == drug_name.lower())].iloc[0]
    drug_name = drug['drug_name']
    medical_condition = drug['medical_condition']
    side_effects = drug['side_effects']
    generic_name = drug['generic_name']
    return drug_name, medical_condition, side_effects, generic_name

while True:
    r = sr.Recognizer()
    with sr.Microphone(sample_rate=48000) as source:
        r.adjust_for_ambient_noise(source)
        r.energy_threshold = 400
        print("Say a drug name!")
        audio = r.listen(source, phrase_time_limit=2)
    
    try:
        text = r.recognize_google(audio)
        print("You said: " + text)
        break
    
    except sr.UnknownValueError:
        print("Sorry, I could not understand what you said. Please try again.")
    
    except sr.RequestError:
        print("Sorry, my speech service is currently down. Please try again later.")

drug_matches = drugs[(drugs['drug_name'].str.lower() == text.lower()) | (drugs['generic_name'].str.lower() == text.lower())]

if len(drug_matches) == 0:
    print("No matching drugs found.")
else:
    print("Matching drugs:")
    for i, drug in enumerate(drug_matches['drug_name'], start=1):
        print(f"{i}. {drug}")

    # Ask the user to select a drug
    while True:
        drug_number = input("Enter the number of the drug you want to learn about (or 'back' to enter a new search): ")
        if drug_number.lower() == 'back':
            break
        
        try:
            drug_index = int(drug_number) - 1
            drug_name, medical_condition, side_effects, generic_name = get_drug_info(drug_matches.iloc[drug_index]['drug_name'])
            print(f"\nDrug Name: {drug_name}\nGeneric Name: {generic_name}\nMedical Condition: {medical_condition}\nSide Effects: {side_effects}\n")
            break
        except ValueError:
            print("Please enter a valid number.")
        except IndexError:
            print("Invalid drug number. Please try again.")
